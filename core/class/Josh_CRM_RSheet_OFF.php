<?php
class JOSH_CRM_RSHEET {
    private $draw;
    private $donorData;
    private $whatsapp;
    private $table_donate;
    private $table_donor;
    private $table_slip;
    private $_table;
    private $_chart;
    private $_allData;

    /**
     * @param string $mode insert 'chart', 'table', or 'allData'
     */
    public function __construct($mode) {

        if($mode === 'chart') {
            $this->_();
            $this->c_();
        } else if($mode === 'table') {
            $this->_();
            $this->t_();
        } else if($mode === 'allData') {
            $this->_();
            $this->a_();
        } else {
            /**
             * @todo error request code
             */
        }
    }

    private function _() {
        $this->draw = $_POST['draw'];
        $this->donorData = $_POST['donorData'];

        global $wpdb;
        $this->table_donate = $wpdb->prefix . 'dja_donate';
        $this->table_donor = $wpdb->prefix . 'josh_donors';
        $this->table_slip = $wpdb->prefix . 'josh_slip';

        $query = "SELECT whatsapp FROM $this->table_donor WHERE id='$this->donorData'";
        $this->whatsapp = $wpdb->get_row($query)->whatsapp;
    }

    /**
     * chart init
     */
    private function c_() {
        global $wpdb;

        $query = "
        SELECT DATE_FORMAT(given_date, '%M') AS month, COUNT(*) AS donate, SUM(nominal) AS volume
        FROM $this->table_slip
        WHERE (given_date BETWEEN DATE_FORMAT(NOW() - INTERVAL 3 MONTH, '%Y-%m-01') AND LAST_DAY(NOW() - INTERVAL 1 MONTH))
        AND (whatsapp='$this->whatsapp')
        GROUP BY DATE_FORMAT(given_date, '%Y-%m')
        ";

        $this->_chart = $wpdb->get_results($query);
    }

    public function c_output() {
        global $wpdb;
        
        $payload = array(
            'dbg'   => $this->_chart,
            'dbg_qr'=> $wpdb->last_query,
            'data'  => array()
        );

        foreach($this->_chart as $data) {
            $data->month = ($data->month == null) ? 0 : $data->month;
            $data->donate = ($data->donate == null) ? 0 : $data->donate;
            $data->volume = ($data->volume == null) ? 0 : $data->volume;

            $payload['data'][] = array(
                'month'     => $data->month,
                'donate'    => intval($data->donate),
                'volume'    => intval($data->volume)
            );
        }

        return $payload;
    }

    /**
     * table init
     * @todo where in time
     */
    private function t_() {
        global $wpdb;

        $query = "SELECT *
        FROM (
            SELECT name, invoice_id, DATE_FORMAT(created_at, '%d %M %Y') AS created_at, DATE_FORMAT(img_confirmation_date, '%d %M %Y') AS img_confirmation_date, NULL AS given_date, 'donate' AS source, nominal
            FROM $this->table_donate
            WHERE $this->table_donate.whatsapp='$this->whatsapp'
            UNION ALL
            SELECT NULL AS name, NULL AS invoice_id, NULL AS created_at, NULL AS img_confirmation_date, DATE_FORMAT(given_date, '%d %M %Y') AS given_date, 'slip' AS source, nominal
            FROM $this->table_slip
            WHERE $this->table_slip.whatsapp='$this->whatsapp'
        ) AS combined
        ORDER BY COALESCE(created_at, given_date) ASC";

        $this->_table = $wpdb->get_results( $query );
    }

    public function t_output() {
        global $wpdb;
        $payload = array(
            'draw' 					=> intval($this->draw),
            'recordsTotal' 			=> 1,
            'recordsFiltered' 		=> 1,
            'error' 				=> '',
            'dbg'                   => $this->_table,
            // 'dbg_er'                => $wpdb->last_error,
            // 'dbg_qr'                => $wpdb->last_query,
            'data'                  => array()
        );

        foreach($this->_table as $data) {
            $nominal = 'Rp' . number_format($data->nominal, 0, ',', '.');

            if($data->source === 'donate' && $data->img_confirmation_date != null) {
                $news = "Order di website atas nama <b>$data->name</b> sebesar $nominal";
                $tgl = $data->created_at;
            }
            else if($data->source === 'donate' && $data->img_confirmation_date == null) {
                $news = "Order di website atas nama <b>$data->name</b> sebesar $nominal";
                $tgl = $data->created_at;
            }
            else if($data->source === 'slip') {
                $news = "Input slip oleh admin pada $data->given_date sebesar $nominal";
                $tgl = $data->given_date;
            }

            $payload['data'][] = array(
                'tgl'           => $tgl,
                'news'          => $news,
                'action'        => 'lihat'
            );
        }

        return $payload;
    }

    /**
     * allData init
     */
    private function a_() {
        global $wpdb;

        /**
         * Donor Table
         * whatsapp, name, owned_by, since, add_reason, tags, city
         */
        {
            $query = "SELECT *, DATE_FORMAT(since, '%d %M %Y') AS _since FROM $this->table_donor WHERE whatsapp='$this->whatsapp'";
    
            $this->_allData['table_donor'] = $wpdb->get_row( $query );
        }

        /**
         * Email
         */
        {
            $query = "SELECT email FROM $this->table_donate WHERE (whatsapp='$this->whatsapp') AND (email IS NOT NULL) ORDER BY id DESC";
            $this->_allData['email'] = $wpdb->get_row($query);
        }

        /**
         * program
         */
        {
            $query = "SELECT a.campaign_id, a.invoice_id, a.name, a.whatsapp, b.abbvr FROM $this->table_donate AS a
            LEFT JOIN `ympb2020_dja_campaign` AS b ON a.campaign_id = b.campaign_id
            WHERE a.whatsapp='$this->whatsapp'
            GROUP BY b.abbvr
            ORDER BY a.id";
            $this->_allData['program'] = $wpdb->get_results($query);
        }

        /**
         * Lifetime Value (LTV), Average Donor Value (ADV), Donor Volume (DVOL)
         */
        {
            $query = "SELECT SUM(nominal) AS ltv, AVG(nominal) AS adv, COUNT(*) AS dvol FROM $this->table_slip WHERE whatsapp='$this->whatsapp'";
            $this->_allData['transaction'] = $wpdb->get_row($query);
        }
    }

    public function a_output() {
        /**
         * program, email
         */
        {
            for($i=0; $i < count($this->_allData['program']); $i++) {
                $program = $program . $this->_allData['program'][$i]->abbvr;

                if($i < count($this->_allData['program']) - 1) {
                    $program = $program . ', ';
                }
            }

            $email = ($this->_allData['email']->email == null) ? '-' : $this->_allData['email']->email;
        }

        /**
         * ltv, adv, dvol
         */
        {
            $ltv = 'Rp ' . number_format(intval($this->_allData['transaction']->ltv), 0, ',', '.');
            $adv = 'Rp ' . number_format(intval($this->_allData['transaction']->adv), 0, ',', '.');
            $dvol = 'Rp ' . number_format(intval($this->_allData['transaction']->dvol), 0, ',', '.');
        }

        /**
         * oName & oEmail
         */
        {
            $csArray = array('husna'=>'Husna','meisya'=>'Meisya','fadhilah'=>'Fadhilah','safina'=>'Safina');
            $emailCs = array('husna'=>'husna@ympb.or.id','meisya'=>'meisya@ympb.or.id','fadhilah'=>'fadhilah@ympb.or.id','safina'=>'safinatun@ympb.or.id');

            $oName = $csArray[$this->_allData['table_donor']->owned_by];
            $oEmail = '(' . $emailCs[$this->_allData['table_donor']->owned_by] . ')';
        }

        return array(
            'status'    => 'success',
            'data'      => array(
                'nama'			=> $this->_allData['table_donor']->name,
                'phone'			=> $this->_allData['table_donor']->whatsapp,
                'category'		=> 'Donatur Tetap (~)',
                'program'		=> $program,
                'email'			=> $email,
                // 'email'			=> 'asrofi@testmail.com',
                'user'			=> 'Tidak',
                'payment'		=> 'Bank Transfer',
                'ltv'			=> $ltv,
                'adv'			=> $adv,
                'dvol'			=> $dvol,
                'dibuat'		=> $this->_allData['table_donor']->_since,
                'dibuat_'		=> '',
                'kota'			=> $this->_allData['table_donor']->city,
                'oName'			=> $oName,
                'oEmail'		=> $oEmail

            )
        );

    }
}