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
    private $date_end_;
    /**
     * Hold filtered clause and each part of it
     * - date (donors, donate)
     * - cs (donors, slip)
     * - show_remove (donors)
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
     * - leads
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
    private $table_mode;

    private $action_remove;

    private $action_update;
    private $dbg;


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
		// $this->date_start   = $_POST['date_start'];
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
        $date_start = "'1970-01'";
        if( $this->date_end == '') {
            $date_end	= "DATE_FORMAT(CURRENT_DATE, '%Y-%m')";
        } else {
            $date_end   = "'$this->date_end'";
        }
        $this->date_end_ = $date_end;

        /**
         * Order Column
         * @todo sesuaikan
         */
        {
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
        }


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
                'donors'	=> " (DATE_FORMAT(since, '%Y-%m') BETWEEN $date_start AND $date_end) ",
                'donate'	=> " (DATE_FORMAT(created_at, '%Y-%m') BETWEEN $date_start AND $date_end) "
            );
            $this->filter['date'] = $date_sql;
        }

        /**
         * 2. CS Filter
         */
        {
            function csFil_gen($filter, $text) {
                if(count($filter->cs) > 0) {
                    $cs_conversion = array('Husna' => 'husna', 'Tisna' => 'fadhilah', 'Meisya' => 'meisya', 'Safina' => 'safina');
                    $owned_by_filter = " (";

                    for($i=0; $i < count($filter->cs); $i++) {
                        if($text === 'relawan') {
                            $owned_by_filter = "$owned_by_filter $text='{$filter->cs[$i]->text}'";
                        } else {
                            $owned_by_filter = "$owned_by_filter $text='{$cs_conversion[$filter->cs[$i]->text]}'";
                        }

                        if($i < count($filter->cs)-1) {
                            $owned_by_filter = $owned_by_filter . ' OR ';
                        } else {
                            $owned_by_filter = $owned_by_filter . ' ) ';
                        }
                    }

                    return $owned_by_filter;
                } else {
                    if($text === 'relawan') {
                        return " ($text='Husna' OR $text='Tisna' OR $text='Meisya' OR $text='Safina') ";
                    } else {
                        return " ($text='husna' OR $text='fadhilah' OR $text='meisya' OR $text='safina') ";
                    }
                }
            }

            $csFil_donors = csFil_gen($filter, 'owned_by');
            $csFil_slip = csFil_gen($filter, 'relawan');

            $filter_cs = array(
                'donors'    => $csFil_donors,
                'slip'      => $csFil_slip
            );
            $this->filter['cs'] = $filter_cs;
        }

        /**
         * 3. Show Remove
         */
        {
            $this->filter['filter'] = '0';
            $this->filter['show_remove'] = array(
                'donors'    => ($this->filter['filter'] === '0') ? ' (remove IS NULL)' : '(remove IS NOT NULL)'
            );
        }

        /**
         * 4. Quick Filter (Name and Whatsapp)
         */
        {
            if($filter->quickSearch == null) {
                $filter->quickSearch = '';
            }

            $quick_filter = array(
                'donors'    => " (name LIKE '%$filter->quickSearch%' or whatsapp LIKE '%$filter->quickSearch%') ",
                'donate'    => " (name LIKE '%$filter->quickSearch%' or whatsapp LIKE '%$filter->quickSearch%') "
            );
            $this->filter['quick_search'] = $quick_filter;
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
                'donors'	=> "{$date_sql['donors']} AND $csFil_donors AND {$quick_filter['donors']}",
                'donate'	=> $date_sql['donate']
            );
            $this->filter['all'] = $all_filter;
            $this->filter['group_by'] = " GROUP BY whatsapp ";
        }

        /**
         * Table Mode
         */
        {
            $this->table_mode = $filter->filterData;
        }

        $this->querying_cards();
	}

    private function querying_cards() {
        global $wpdb;

        if($this->table_mode == null) {
            //
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
                    WHERE MONTH(s.given_date) = MONTH($this->date_end_) 
                    AND YEAR(s.given_date) = YEAR($this->date_end_)
                )
                AND {$this->filter['show_remove']['donors']}
                AND ({$this->filter['cs']['donors']})
                AND DATE_FORMAT(c.since, '%Y-%m') < $this->date_end_";

                $row = $wpdb->get_row($query)->belum_tf;
                $this->query_card['belum_tf'] = strval(number_format($row, 0, ',', '.'));
            }
    
    
            /**
             * 2. Nomor Dikelola
             */
            {
                $sql = "SELECT COUNT(*) as nomor_dikelola
                FROM $this->table_donors
                WHERE {$this->filter['cs']['donors']}
                AND (DATE_FORMAT(since, '%Y-%m') <= $this->date_end_)
                AND {$this->filter['show_remove']['donors']}";
    
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
                    WHERE (DATE_FORMAT(s.given_date, '%Y-%m') <= $this->date_end_)
                )
                AND (DATE_FORMAT(d.since, '%Y-%m') <= $this->date_end_)
                AND {$this->filter['show_remove']['donors']}
                AND ({$this->filter['cs']['donors']})";

                $row = $wpdb->get_row($query)->dn_aktif;
                $this->query_card['donatur_aktif'] = intval($row);
                $this->query_card['donatur_aktif_f'] = strval(number_format($row, 0, ',', '.'));
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
                $query = "SELECT SUM(nominal) as dod
                FROM $this->table_slip
                WHERE platform='Organik'
                AND {$this->filter['cs']['slip']}
                AND (DATE_FORMAT(given_date, '%Y-%m') = $this->date_end_)";
                
                $nominal = $wpdb->get_row( $query )->dod;
    
                $query = "SELECT COUNT(*) as n_dikelola
                FROM $this->table_donors
                WHERE (remove IS NULL)
                AND ({$this->filter['cs']['donors']})
                AND (DATE_FORMAT(since, '%Y-%m') < $this->date_end_)";
    
                $nomor_dikelola_since_last_month = $wpdb->get_row($query)->n_dikelola;
    
                $DOD = $nominal / $nomor_dikelola_since_last_month;
                $this->query_card['DOD'] = strval(number_format($DOD, 0, ',', '.'));
            }
    
            /**
             * 6. DID (Donasi Iklan per Donatur)
             */
            {
                $query = "SELECT AVG(nominal) as did
                FROM $this->table_slip
                WHERE (platform='Iklan')
                AND {$this->filter['cs']['slip']}
                AND (DATE_FORMAT(given_date, '%Y-%m') = $this->date_end_)";
    
                $DID = $wpdb->get_row( $query )->did;
                $this->query_card['DID'] = strval( number_format( $DID, 0, ',', '.' ) );
            }
    
            /**
             * 7. Leads
             */
            {
                $query = "SELECT COUNT(*) as leads
                FROM $this->table_donors
                WHERE (remove IS NULL)
                AND {$this->filter['cs']['donors']}
                AND (DATE_FORMAT(since, '%Y-%m') = $this->date_end_)";
    
                $leads = $wpdb->get_row( $query )->leads;
                $this->query_card['leads'] = strval( number_format( $leads, 0, ',', '.') );
            }
    
            /**
             * 8 Omset
             */
            {
                $query = "SELECT SUM(nominal) as omset
                FROM $this->table_slip
                WHERE {$this->filter['cs']['slip']}
                AND (DATE_FORMAT(given_date, '%Y-%m') = $this->date_end_)";
                $omset = $wpdb->get_row( $query )->omset;
    
                $this->query_card['omset'] = strval( number_format( $omset, 0, ',', '.' ) );
            }
        
        } else {
            $this->query_card['belum_tf'] = '...';
            $this->query_card['nomor_dikelola'] = '...';
            $this->query_card['nomor_dikelola_f'] = '...';
            $this->query_card['donatur_aktif'] = '...';
            $this->query_card['donatur_aktif_f'] = '...';
            $this->query_card['closing_rate'] = '...';
            $this->query_card['DOD'] = '...';
            $this->query_card['DID'] = '...';
            $this->query_card['leads'] = '...';
            $this->query_card['omset'] = '...';
        }


        $this->querying_table();
    }

    private function querying_table() {
        global $wpdb;

        if($this->table_mode == null) {
            /**
             * 1. Before Filter (recordsTotal) | Mode Normal
             */
            {
                $sql = "SELECT COUNT(*) as row_before
                FROM $this->table_donors
                WHERE {$this->filter['date']['donors']}
                AND (remove IS NULL)";
    
                $this->query_table['row_before'] = $wpdb->get_row($sql)->row_before;
            }
            
            /**
             * 2. After Filter (recordsFiltered) | Mode Normal
             */
            {
                $query = "SELECT COUNT(*) as row_filter
                FROM $this->table_donors
                WHERE {$this->filter['all']['donors']}
                AND (remove IS NULL)";
    
                $this->query_table['row_filter'] = $wpdb->get_row($query)->row_filter;
                // if($this->undonate === false ) {
                // } else {
                //     $query = "SELECT COUNT(*) as row_filter
                //     FROM $this->table_donors as d
                //     WHERE (DATE_FORMAT(d.since, '%Y-%m') < DATE_FORMAT(CURRENT_DATE, '%Y-%m'))
                //     AND {$this->filter['show_remove']['donors']}
                //     AND ({$this->filter['cs']['donors']})
                //     AND (d.whatsapp NOT IN (
                //         SELECT s.whatsapp
                //         FROM $this->table_slip as s
                //         WHERE DATE_FORMAT(s.given_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m')
                //     ))";
    
                //     $this->query_table['row_filter'] = $wpdb->get_row($query)->row_filter;
                // }
            }
    
            
            /**
             * 3. TO PRESENT (with Limit) | Mode Normal
             */
            {
                $sql = "SELECT *
                FROM $this->table_donors
                WHERE {$this->filter['all']['donors']}
                AND (remove IS NULL)
                ORDER BY since DESC
                LIMIT $this->start, $this->length";
                // if($this->undonate === false) {
                // } else {
                //     $sql = "SELECT *
                //     FROM $this->table_donors as d
                //     WHERE (DATE_FORMAT(d.since, '%Y-%m') < DATE_FORMAT(CURRENT_DATE, '%Y-%m'))
                //     AND {$this->filter['show_remove']['donors']}
                //     AND ({$this->filter['cs']['donors']})
                //     AND (d.whatsapp NOT IN (
                //         SELECT s.whatsapp
                //         FROM $this->table_slip as s
                //         WHERE DATE_FORMAT(s.given_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m')
                //     ))
                //     ORDER BY since DESC
                //     LIMIT $this->start, $this->length";
                // }
    
                $this->query_table['row_present'] = $wpdb->get_results($sql);
            }
    
    
            // /** moved after else if
            //  * Count of array
            //  */
            // {
            //     $i = 1;
            //     $this->query_table['count_before'] = intval($this->query_table['row_before']);
            //     $this->query_table['count_filter'] = intval($this->query_table['row_filter']);
            //     $this->query_table['count_present'] = count($this->query_table['row_present']);
            // }

        } elseif($this->table_mode === 'not-donate') {

            /**
             * 1. Before Filter (recordsTotal) | Mode Not Repeat Donate
             */
            {
                $sql = "SELECT COUNT(*) as row_before
                FROM $this->table_donors
                WHERE {$this->filter['date']['donors']}
                AND (remove IS NULL)";
    
                $this->query_table['row_before'] = $wpdb->get_row($sql)->row_before;
            }

            /**
             * 2. After Filter (recordsFiltered) | Mode Not Repeat Donate
             */
            {
                // if($this->undonate === false ) {
                //     $query = "SELECT COUNT(*) as row_filter
                //     FROM $this->table_donors
                //     WHERE {$this->filter['all']['donors']}";
        
                //     $this->query_table['row_filter'] = $wpdb->get_row($query)->row_filter;
                // } else {
                //     $query = "SELECT COUNT(*) as row_filter
                //     FROM $this->table_donors as d
                //     WHERE (DATE_FORMAT(d.since, '%Y-%m') < DATE_FORMAT(CURRENT_DATE, '%Y-%m'))
                //     AND {$this->filter['show_remove']['donors']}
                //     AND ({$this->filter['cs']['donors']})
                //     AND (d.whatsapp NOT IN (
                //         SELECT s.whatsapp
                //         FROM $this->table_slip as s
                //         WHERE DATE_FORMAT(s.given_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m')
                //     ))";
    
                //     $this->query_table['row_filter'] = $wpdb->get_row($query)->row_filter;
                // }
                $query = "SELECT COUNT(*) as row_filter
                FROM $this->table_donors as d
                WHERE (DATE_FORMAT(d.since, '%Y-%m') < DATE_FORMAT(CURRENT_DATE, '%Y-%m'))
                AND (remove IS NULL)
                AND ({$this->filter['cs']['donors']})
                AND (d.whatsapp NOT IN (
                    SELECT s.whatsapp
                    FROM $this->table_slip as s
                    WHERE DATE_FORMAT(s.given_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m')
                ))";

                $this->query_table['row_filter'] = $wpdb->get_row($query)->row_filter;
            }

            /**
             * 3. TO PRESENT (with Limit) | Mode Not Repeat Donate
             */
            {
                // if($this->undonate === false) {
                //     $sql = "SELECT *
                //     FROM $this->table_donors
                //     WHERE {$this->filter['all']['donors']}
                //     ORDER BY since DESC
                //     LIMIT $this->start, $this->length";
                // } else {
                // }
                $sql = "SELECT *
                FROM $this->table_donors as d
                WHERE (DATE_FORMAT(d.since, '%Y-%m') < DATE_FORMAT(CURRENT_DATE, '%Y-%m'))
                AND (remove IS NULL)
                AND ({$this->filter['cs']['donors']})
                AND (d.whatsapp NOT IN (
                    SELECT s.whatsapp
                    FROM $this->table_slip as s
                    WHERE DATE_FORMAT(s.given_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m')
                ))
                ORDER BY since DESC
                LIMIT $this->start, $this->length";
    
                $this->query_table['row_present'] = $wpdb->get_results($sql);
            }

        } elseif($this->table_mode === 'removed') {

            /**
             * 1. Before Filter (recordsTotal) | Mode Removed
             */
            {
                $sql = "SELECT COUNT(*) as row_before
                FROM $this->table_donors
                WHERE {$this->filter['date']['donors']}
                AND (remove IS NOT NULL)";
    
                $this->query_table['row_before'] = $wpdb->get_row($sql)->row_before;
            }

            /**
             * 2. After Filter (recordsFiltered) | Mode Removed
             */
            {
                $query = "SELECT COUNT(*) as row_filter
                FROM $this->table_donors
                WHERE {$this->filter['all']['donors']}
                AND (remove IS NOT NULL)";

                $this->query_table['row_filter'] = $wpdb->get_row($query)->row_filter;
            }

            /**
             * 3. TO PRESENT (with Limit) | Mode Removed
             */
            {
                $sql = "SELECT *
                FROM $this->table_donors
                WHERE {$this->filter['all']['donors']}
                AND (remove IS NOT NULL)
                ORDER BY since DESC
                LIMIT $this->start, $this->length";
    
                $this->query_table['row_present'] = $wpdb->get_results($sql);
            }

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
            'leads'             => $this->query_card['leads'],
            'omset'				=> $this->query_card['omset'],
            'closingRate'		=> $this->query_card['closing_rate'],
            'semuaNomor'		=> $this->query_card['nomor_dikelola_f'],
            "draw"				=> $this->draw,
            "recordsTotal"		=> $this->query_table['count_before'],
            "recordsFiltered"	=> $this->query_table['count_filter'],
            "date_start"		=> $this->query_table['date_start'],
            "date_end"			=> $this->query_table['date_end'],
            "tableMode"         => $this->table_mode,
            "dbg"               => $this->dbg,
            "data"				=> array()
        );

        $i = 1 + $this->start;
    
        foreach($this->query_table['row_present'] as $data) {
    
            $donor = $this->querying_each_donor($data);
            
            /**
             * Insert it to container $payload
             */
            $payload['data'][] = array(
                'chk'			=> $i,
                'id'            => $data->id,
                'nama'			=> $data->name,
                'tags'			=> $donor['tags'],
                'noWa'			=> $data->whatsapp,
                'pemilik'		=> $data->owned_by,
                'totalOrder'    => $donor['totalOrder'],
                'nilaiOrder'	=> $donor['nilaiOrder'],
                'totalSlip'		=> $donor['totalSlip'],
                'nilaiSlip'	    => $donor['nilaiSlip'],
                'dibuat'		=> $donor['dibuat'],
                'tableMode'     => $this->table_mode,
                'removeReason'  => $data->remove_reason
            );

            $i++;
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

        // 2. total & nilai order
        {
            $sql = "SELECT COUNT(*) as totalOrder, SUM(nominal) as nilaiOrder
            FROM $this->table_donate
            WHERE {$this->filter['all']['donate']}
            AND (whatsapp='$data->whatsapp')";

            $row = $wpdb->get_row($sql);
            $totalOrder = number_format( intval($row->totalOrder), 0, ',', '.' );
            $nilaiOrder = 'Rp'.number_format( intval($row->nilaiOrder), 0, ',', '.' );
        }

        // 3. nilai & total slip
        {
            $query = "SELECT SUM(nominal) as nilaiSlip, COUNT(*) as totalSlip
            FROM $this->table_slip
            WHERE whatsapp='$data->whatsapp'";
    
            $row = $wpdb->get_row($query);
            $totalSlip = number_format(intval($row->totalSlip), 0, ',', '.');
            $nilaiSlip = 'Rp' . number_format(intval($row->nilaiSlip), 0, ',', '.');
        }

        // 4. dibuat
        $dibuat = date( 'd M Y', strtotime( $data->since ) );
        $dibuat = str_replace(' ', '&nbsp;', $dibuat);
        $dibuat = $dibuat . ' ' . date( 'H:i:s', strtotime( $data->since ) );

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

            case 'restore':
                $this->_restore();
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

    private function _restore() {
        global $wpdb;

        $this->action_update = $wpdb->update(
            $this->table_donors,
            array(
                'remove'        => null,
                'remove_reason' => null,
                'remove_dt'     => null,
                'remove_by'     => null
            ),
            array(
                'id'            => $_POST['donor_id']
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