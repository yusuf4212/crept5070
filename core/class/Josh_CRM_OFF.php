<?php
class JOSH_CRM {
	private $table_donate;
	private $table_slip;
	private $table_donors;
	private $draw;
	private $start;
	private $length;
	private $search;
	private $order_col;
	private $order_dir;
	private $date_start;
	private $date_end;
    /**
     * Hold filtered clause and each part of it
     * - date (donors, donate)
     * - cs
     * - group_by
     * - all (donors, donate)
     * @var $filter
     */
	private $filter;
    /**
     * Hold sql query result for card data
     * - donors_exc => retreive all donors donated before this month
     * - donors_exc_sc
     * - donors_th_month => retreive all donate this month (can specific) only
     * - donors_since_last_month_donate_this_month => Find the difference (donor don't make any donation)
     * - belum_tf
     * - nomor_dikelola
     * - donatur_aktif
     * - closing_rate
     * - DOD
     * - DID
     * - omset
     */
    private $query_card;
    /**
     * Hold sql query result for table data
     * - row_before => Before Filter (recordsTotal)
     * - row_filter => After Filter (recordsFiltered)
     * - row_present => TO PRESENT (with Limit)
     * - count_before
     * - count_filter
     * - count_present
     */
    private $query_table;

    /**
     * Undonate
     * true if checked, false if not
     */
    private $undonate;

    private $action_remove;

    private $action_update;


	public function __construct() {
		global $wpdb;
		$this->table_donate = $wpdb->prefix . 'dja_donate';
		$this->table_slip = $wpdb->prefix . 'josh_slip';
		$this->table_donors = $wpdb->prefix . 'josh_donors';

        if($_POST['crm_action'] != null) {
            $this->_action();
            return;
        }

		/**
		 * Get All Request
		 */
		$this->draw		    = $_POST['draw'];
		$this->start		= $_POST['start'];
		$this->length		= $_POST['length'];
		$this->search		= $_POST['search']['value'];
		$this->order_col	= $_POST['order']['0']['column'];
		$this->order_dir	= $_POST['order']['0']['dir'];
		$this->date_start   = $_POST['date_start'];
		$this->date_end	    = $_POST['date_end'];
		$filter		        = $_POST['filter'];
        $this->undonate     = ($_POST['undonate'] === 'on') ? true : false;

        $this->filter($filter);
	}


    /**
     * BEGIN GIVE DONOR LIST FUNCTIONALITY
     * 
     * 
     */
	public function filter($filter) {
		// set this month if there is no request (default)
        if( $this->date_start == '') {
            $date_start = date('1970-01-01') . ' 00:00:00';
            $date_end	= date('Y-m-d') . ' 23:59:59';
        } else {
            $date_start = $this->date_start;
            $date_end   = $this->date_end;
        }

        /**
         * Order Column
         * @todo sesuaikan
         */
        // $order_col = $this->order_col;
        // if( $order_col == 0) $order_colName = 'id';
        // elseif( $order_col == 1) $order_colName = 'relawan';
        // elseif( $order_col == 2) $order_colName = 'given_date';
        // elseif( $order_col == 3) $order_colName = 'program';
        // elseif( $order_col == 4) $order_colName = 'type';
        // elseif( $order_col == 5) $order_colName = 'platform';
        // elseif( $order_col == 7) $order_colName = 'nominal';
        // elseif( $order_col == 8) $order_colName = 'bank';
        // elseif( $order_col == 9) $order_colName = 'transfer_date';
        // elseif( $order_col == 10) $order_colName = 'whatsapp';


        /**
         * Filter Init
         * Convert from JSON format to Object and Array!
         */
        {
            $filter = stripcslashes($filter);
            if ($filter != '') {
                $filter = json_decode($filter);
            } else {
                $filter = '';
            }
        }

        /**
         * FILTER GENERATOR
         * @todo provide full filter
         * 
         * 1. Date Filter
         */
        {
            $date_sql = array(
                'donors'	=> " (since BETWEEN '$date_start' AND '$date_end') ",
                'donate'	=> " (created_at BETWEEN '$date_start' AND '$date_end') "
            );
            $this->filter['date'] = $date_sql;
        }

        /**
         * 2. CS Filter
         */
        {
            if(count($filter->cs) > 0) {
                $owned_by_filter = " (";
                for($i=0; $i < count($filter->cs); $i++) {
                    $cs_conversion = array('Husna' => 'husna', 'Tisna' => 'fadhilah', 'Meisya' => 'meisya', 'Safina' => 'safina');
                    $owned_by_filter = $owned_by_filter . "owned_by='" . $cs_conversion[$filter->cs[$i]->text] . "'";
                    if($i < count($filter->cs)-1) {
                        $owned_by_filter = $owned_by_filter . ' OR ';
                    } else {
                        $owned_by_filter = $owned_by_filter . ') ';
                    }
                }
            } else {
                $owned_by_filter = " (owned_by='husna' OR owned_by='fadhilah' OR owned_by='meisya' OR owned_by='safina') ";
            }
            $this->filter['cs'] = $owned_by_filter;
        }


        /**
         * COMBINE ALL FILTER
         * 
         * and
         * 
         * GROUP BY
         * 
         */
        {
            $all_filter = array(
                'donors'	=> $date_sql['donors'] . ' AND ' . $owned_by_filter,
                'donate'	=> $date_sql['donate']
            );
            $this->filter['all'] = $all_filter;
            $this->filter['group_by'] = " GROUP BY whatsapp ";
        }

        $this->querying_cards();
	}

    private function querying_cards() {
        global $wpdb;

        /**
         * Generate and formatting data for cards
         * 1. belum transfer
         * c.id, c.name, c.whatsapp, DATE_FORMAT(c.since, '%d %M %Y') AS since, c.owned_by
         */
        {
            $query = "SELECT COUNT(*) AS belum_tf
            FROM $this->table_donors AS c
            WHERE c.whatsapp NOT IN (
                SELECT s.whatsapp
                FROM $this->table_slip AS s
                WHERE MONTH(s.given_date) = MONTH(CURRENT_DATE) 
                AND YEAR(s.given_date) = YEAR(CURRENT_DATE)
            )
            AND ({$this->filter['cs']})
            AND DATE_FORMAT(c.since, '%Y-%m') < DATE_FORMAT(CURRENT_DATE, '%Y-%m')";
            $row = $wpdb->get_row($query)->belum_tf;
            $this->query_card['belum_tf'] = strval(number_format($row, 0, ',', '.'));
        }

        {
            // // 1.1 retreive all donors donated before this month
            $first_in_month = date('Y-m-01') . ' 00:00:00';
            // $sql = "SELECT DISTINCT whatsapp FROM $this->table_donors WHERE (since < '$first_in_month') AND {$this->filter['cs']}";
            // $this->query_card['donors_exc'] = $wpdb->get_results($sql);
    
            // // scalar array
            // $donors_exc_sc = array_map( function($donor) {
            //     return $donor->whatsapp;
            // }, $this->query_card['donors_exc'] );
            // $this->query_card['donors_exc_sc'] = $donors_exc_sc;
    
            // // 1.2 retreive all donate this month only
            $today = date('Y-m-d') . ' 23:59:59';
            // $sql = " SELECT DISTINCT whatsapp FROM $this->table_slip WHERE given_date BETWEEN '$first_in_month' and '$today'";
            // $this->query_card['donors_th_month'] = $wpdb->get_results( $sql );
    
            // // scalar array
            // $this->query_card['donors_th_month_sc'] = array_map( function ($donor) {
            //     return $donor->whatsapp;
            // }, $this->query_card['donors_th_month'] );
    
            // // 1.3 Find the difference (donor don't make any donation)
            // $this->query_card['donors_since_last_month_donate_this_month'] = array_intersect( $this->query_card['donors_exc_sc'], $this->query_card['donors_th_month_sc']);
    
            // // 1.4 result (belum tf)
            // $belum_tf = count( $this->query_card['donors_exc_sc'] ) - count( $this->query_card['donors_since_last_month_donate_this_month'] );
            // $this->query_card['belum_tf'] = strval( number_format( $belum_tf, 0, ',', '.' ) );
        }

        /**
         * 2. Nomor Dikelola
         */
        {
            $sql = "SELECT COUNT(*) as nomor_dikelola FROM $this->table_donors WHERE {$this->filter['cs']}";
            $this->query_card['nomor_dikelola'] = intval($wpdb->get_row($sql)->nomor_dikelola);
            $this->query_card['nomor_dikelola_f'] = strval( number_format( $this->query_card['nomor_dikelola'], 0, ',', '.' ) );
        }


        /**
         * 3. Mencari donatur aktif
         */
        {
            $query = "SELECT COUNT(*) AS dn_aktif
            FROM $this->table_donors AS d
            WHERE d.whatsapp IN (
                SELECT s.whatsapp
                FROM $this->table_slip AS s
            )
            AND ({$this->filter['cs']})";
            $row = $wpdb->get_row($query)->dn_aktif;
            $this->query_card['donatur_aktif'] = intval($row);
            $this->query_card['donatur_aktif_f'] = strval(number_format($row, 0, ',', '.'));
        }

        {
            // // 3.1 Retreive all donors
            // $query = "SELECT whatsapp FROM $this->table_donors WHERE {$this->filter['cs']}";
            // $all_donors = $wpdb->get_results( $query );
            // // scalar array
            // $all_donors_sc = array_map( function( $donor ) {
            //     return $donor->whatsapp;
            // }, $all_donors );
    
            // // 3.2 retreive all slip whatsapp number
            // $query = "SELECT whatsapp FROM $this->table_slip";
            // $all_slip_wa = $wpdb->get_results( $query );
            // // scalar array
            // $all_slip_wa_sc = array_map( function( $donor ) {
            //     return $donor->whatsapp;
            // }, $all_slip_wa );
    
            // // 3.3 find the difference (search for active donors)
            // $active_donors = array_intersect( $all_donors_sc, $all_slip_wa_sc );
    
            // // 3.4 result
            // $active_donors_cnt = count( $active_donors );
            // $this->query_card['donatur_aktif'] = strval( number_format( $active_donors_cnt, 0, ',', '.' ) );
        }


        /**
         * 4. Closing Rate
         */
        {
            $closing_rate = $this->query_card['donatur_aktif'] / $this->query_card['nomor_dikelola'];
            $closing_rate = number_format( $closing_rate * 100, 2, ',', '.' );
            $this->query_card['closing_rate'] = strval( $closing_rate );
        }

        /**
         * 5. DUD (Donasi Ulang per Donatur) or DOD (Donasi Organik per Donatur)
         */
        {
            $query = "SELECT AVG(nominal) as dod
            FROM $this->table_slip
            WHERE platform='Organik'
            AND (given_date BETWEEN '$first_in_month' and '$today')";
            $DOD = $wpdb->get_row( $query )->dod;
            $this->query_card['DOD'] = strval(number_format( $DOD, 0, ',', '.' ));
        }

        /**
         * 6. DID (Donasi Iklan per Donatur)
         */
        {
            $query = "SELECT AVG(nominal) as did FROM $this->table_slip WHERE (platform='Iklan') AND (given_date BETWEEN '$first_in_month' and '$today')";
            $DID = $wpdb->get_row( $query )->did;
            $this->query_card['DID'] = strval( number_format( $DID, 0, ',', '.' ) );
        }

        /**
         * 8 Omset
         */
        {
            $query = "SELECT SUM(nominal) as omset FROM $this->table_slip WHERE given_date BETWEEN '$first_in_month' and '$today'";
            $omset = $wpdb->get_row( $query )->omset;
            $this->query_card['omset'] = strval( number_format( $omset, 0, ',', '.' ) );
        }


        $this->querying_table();
    }

    private function querying_table() {
        global $wpdb;

        /**
         * Before Filter (recordsTotal)
         */
        {
            $sql = "SELECT COUNT(*) as row_before
            FROM $this->table_donors
            WHERE {$this->filter['date']['donors']}";

            $this->query_table['row_before'] = $wpdb->get_row($sql)->row_before;
        }
        
        /**
         * After Filter (recordsFiltered)
         */
        {
            if($this->undonate === false ) {
                $sql = "SELECT COUNT(*) as row_filter
                FROM $this->table_donors
                WHERE {$this->filter['all']['donors']}";
    
                $this->query_table['row_filter'] = $wpdb->get_row($sql)->row_filter;
            } else {
                $query = "SELECT COUNT(*) as row_filter
                FROM $this->table_donors as d
                WHERE (DATE_FORMAT(d.since, '%Y-%m') < DATE_FORMAT(CURRENT_DATE, '%Y-%m'))
                AND ({$this->filter['cs']})
                AND (d.whatsapp NOT IN (
                    SELECT s.whatsapp
                    FROM $this->table_slip as s
                    WHERE DATE_FORMAT(s.given_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m')
                ))";

                $this->query_table['row_filter'] = $wpdb->get_row($query)->row_filter;
                $this->query_table['dbg'] = $query;
            }
        }

        
        /**
         * TO PRESENT (with Limit)
         */
        {
            if($this->undonate === false) {
                $sql = "SELECT *
                FROM $this->table_donors
                WHERE {$this->filter['all']['donors']}
                ORDER BY since DESC
                LIMIT $this->start, $this->length";
            } else {
                $sql = "SELECT *
                FROM $this->table_donors as d
                WHERE (DATE_FORMAT(d.since, '%Y-%m') < DATE_FORMAT(CURRENT_DATE, '%Y-%m'))
                AND ({$this->filter['cs']})
                AND (d.whatsapp NOT IN (
                    SELECT s.whatsapp
                    FROM $this->table_slip as s
                    WHERE DATE_FORMAT(s.given_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m')
                ))
                ORDER BY since DESC
                LIMIT $this->start, $this->length";
            }

            $this->query_table['row_present'] = $wpdb->get_results($sql);
        }


        /**
         * Count of array
         */
        {
            $i = 1;
            $this->query_table['count_before'] = intval($this->query_table['row_before']);
            $this->query_table['count_filter'] = intval($this->query_table['row_filter']);
            $this->query_table['count_present'] = count($this->query_table['row_present']);
        }

    }

    public function output() {
        $payload = array(
            'dnAktif'			=> $this->query_card['donatur_aktif_f'],
            'belumTf'			=> $this->query_card['belum_tf'],
            'DOD'				=> $this->query_card['DOD'],
            'DID'				=> $this->query_card['DID'],
            'omset'				=> $this->query_card['omset'],
            'closingRate'		=> $this->query_card['closing_rate'],
            'semuaNomor'		=> $this->query_card['nomor_dikelola_f'],
            "draw"				=> $this->draw,
            "recordsTotal"		=> $this->query_table['count_before'],
            "recordsFiltered"	=> $this->query_table['count_filter'],
            "date_start"		=> $this->query_table['date_start'],
            "date_end"			=> $this->query_table['date_end'],
            "dbg"               => $this->query_table['dbg'],
            "data"				=> array()
        );
    
        foreach($this->query_table['row_present'] as $data) {
    
            $donor = $this->querying_each_donor($data);
            
            /**
             * Insert it to container $payload
             */
            $payload['data'][] = array(
                'chk'			=> 'chk',
                'id'            => $data->id,
                'nama'			=> $data->name,
                'tags'			=> $donor['tags'],
                'noWa'			=> $data->whatsapp,
                'pemilik'		=> $data->owned_by,
                'totalOrder'    => $donor['totalOrder'],
                'nilaiOrder'	=> $donor['nilaiOrder'],
                'totalSlip'		=> $donor['totalSlip'],
                'nilaiSlip'	    => $donor['nilaiSlip'],
                'dibuat'		=> $donor['dibuat']
            );
        }
    
        return $payload;
    }

    private function querying_each_donor($data) {
        global $wpdb;

        /**
         * Generate and formatting data for each donors
         * 1. Tags
         */
        if( $data->tags == null || $data->tags == '' ) {
            $tags = '-';
        } else {
            $tags = stripslashes( $data->tags );
        }

        // 2. total slip
        {
            // $sql1 = "SELECT COUNT(*) as totalSlip
            // FROM $this->table_donate
            // WHERE {$this->filter['all']['donate']}
            // AND (whatsapp='$data->whatsapp')";
    
            // $totalSlip = $wpdb->get_row($sql1)->totalSlip;
            // $totalSlip = strval($totalSlip);
        }
    
        // 3. nilai order
        {
            $sql = "SELECT COUNT(*) as totalOrder, SUM(nominal) as nilaiOrder
            FROM $this->table_donate
            WHERE {$this->filter['all']['donate']}
            AND (whatsapp='$data->whatsapp')";

            $row = $wpdb->get_row($sql);
            $totalOrder = number_format( intval($row->totalOrder), 0, ',', '.' );
            $nilaiOrder = 'Rp'.number_format( intval($row->nilaiOrder), 0, ',', '.' );
        }

        // // 4. total slip
        // {
        //     $query = "SELECT COUNT(*) as totalSlip
        //     FROM $this->table_slip
        //     WHERE whatsapp='$data";
        // }

        // 4. nilai & total slip
        {
            $query = "SELECT SUM(nominal) as nilaiSlip, COUNT(*) as totalSlip
            FROM $this->table_slip
            WHERE whatsapp='$data->whatsapp'";
    
            $row = $wpdb->get_row($query);
            $totalSlip = number_format(intval($row->totalSlip), 0, ',', '.');
            $nilaiSlip = 'Rp' . number_format(intval($row->nilaiSlip), 0, ',', '.');
        }

        // 5. dibuat
        $dibuat = date( 'd M Y', strtotime( $data->since ) );

        return array(
            'tags'          => $tags,
            'totalOrder'    => $totalOrder,
            'nilaiOrder'    => $nilaiOrder,
            'totalSlip'     => $totalSlip,
            'nilaiSlip'     => $nilaiSlip,
            'dibuat'        => $dibuat
        );
    }

    
    /**
     * BEGIN ACTION FUNCTIONALITY
     * 
     * 
     */
    private function _action() {
        switch ($_POST['crm_action']) {
            case 'remove':
                $this->_remove();
                break;
            
            default:
                $this->_action_err();
                break;
        }
    }

    private function _remove() {
        global $wpdb;
        $reason = substr($_POST['reason'], 0, 300);

        $this->action_update = $wpdb->update(
            $this->table_donors,
            array(
                'remove'        => 1,
                'remove_reason' => $reason,
                'remove_dt'     => date('Y-m-d H:i:s'),
                'remove_by'     => intval(wp_get_current_user()->ID)
            ),
            array(
                'id'        => $_POST['donor_id']
            )
        );
    }

    public function output_action() {
        if($this->action_update === false) {
            return array(
                'status'    => 'failed',
                'response'   => array(
                    'title'  => 'Updating Database Failed!',
                    'messages' => 'Error while updating data in database!'
                )
            );
        } else {
            return array(
                'status'    => 'success'
            );
        }
    }

    private function _action_err() {
        
    }
}
