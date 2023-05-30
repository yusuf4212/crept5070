<?php
class JOSH_CRM_RSHEET {
    private $draw;
    private $donorData;
    private $whatsapp;
    private $table_donate;
    private $table_donor;
    private $table_slip;
    private $table_campaign;
    private $table_followup;
    private $_table;
    private $_chart;
    private $_allData;
    private $dbg;

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
        $this->table_campaign = $wpdb->prefix . 'dja_campaign';
        $this->table_followup = $wpdb->prefix . 'josh_cs_f';

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
            SELECT name, invoice_id, DATE_FORMAT(created_at, '%d %M %Y') AS created_at, DATE_FORMAT(img_confirmation_date, '%d %M %Y') AS img_confirmation_date, NULL AS id, NULL AS given_date, 'donate' AS source, nominal
            FROM $this->table_donate
            WHERE $this->table_donate.whatsapp='$this->whatsapp'
            UNION ALL
            SELECT NULL AS name, NULL AS invoice_id, NULL AS created_at, NULL AS img_confirmation_date, id, DATE_FORMAT(given_date, '%d %M %Y') AS given_date, 'slip' AS source, nominal
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
            // 'dbg'                   => $this->dbg,
            // 'dbg_er'                => $wpdb->last_error,
            // 'dbg_qr'                => $wpdb->last_query,
            'data'                  => array()
        );

        foreach($this->_table as $data) {
            if($data->source === 'donate') {
                $order = $this->_t_output($data->invoice_id, 'order');
                $fwp = $this->__t_output($data->invoice_id);
                $news = "Order di website atas nama <b>$data->name</b> sebesar <b>Rp".number_format($order->nominal, 0, ',', '.')."</b> untuk program <b>$order->abbvr</b>";
                
                $payload['data'][] = array(
                    'tgl'           => str_replace(' ', '&nbsp;', $data->created_at),
                    'news'          => $news,
                    'receipt'       => $order->img_confirmation_url,
                    'action'        => '',
                    'source'        => 'order',
                    'invoice'       => $order->invoice_id,
                    'name'          => $order->name,
                    'whatsapp'      => $order->whatsapp,
                    'program'       => $order->title,
                    'nominal'       => number_format($order->nominal, 0, ',', '.'),
                    'ulang'         => ($order->repeat_sts === 'new') ? 'Baru' : 'Ulang #' . $order->repeat_no,
                    'email'         => $order->email,
                    'doa'           => $order->comment,
                    'waktu'         => date('d M Y H:i:s', strtotime($order->created_at)),
                    'anonim'        => ($order->anonim === 1) ? 'Ya' : 'Tidak',
                    'cs'            => get_user_by('ID', $order->cs_id)->data->display_name,
                    'fwp'           => $fwp,
                    'waba'          => ($order->status_sent_order == null) ? '' : $order->status_sent_order,
                    'ref'           => ($order->ref == null) ? '' : $order->ref,
                    'utmSource'     => ($order->utm_source == null) ? '' : $order->utm_source,
                    'utmMedium'     => ($order->utm_medium == null) ? '' : $order->utm_medium,
                    'utmCampaign'   => ($order->utm_campaign == null) ? '' : $order->utm_campaign,
                    'utmTerm'       => ($order->utm_term == null) ? '' : $order->utm_term,
                    'utmContent'    => ($order->utm_content == null) ? '' : $order->utm_content,
                    'city'          => ($order->city == null) ? '' : $order->city,
                    'provider'      => ($order->provider == null) ? '' : $order->provider,
                    'os'            => ($order->operating_system == null) ? '' : $order->operating_system,
                    'ipAddress'     => $order->ip_address,
                    'browser'       => $order->browser,
                    'mobile'        => $order->mobdesk
                );
            }
            else if($data->source === 'slip') {
                $slip = $this->_t_output($data->id, 'slip');
                $news = "Input slip oleh admin sebesar <b>".number_format($slip->nominal, 0, ',', '.')."</b> dengan platform <b>$slip->platform</b>";
                
                $payload['data'][] = array(
                    'tgl'           => $data->given_date,
                    'news'          => $news,
                    'action'        => '',
                    'source'        => 'slip',
                    'whatsapp'      => $slip->whatsapp,
                    '_name'         => $slip->name,
                    'relawan'       => $slip->relawan,
                    'givenDate'     => date('d M Y', strtotime($slip->given_date)),
                    'program'       => $slip->program,
                    'platform'      => $slip->platform,
                    'slipAddress'   => $slip->slip_address,
                    'nominal'       => number_format($slip->nominal, 0, ',', '.'),
                    'bank'          => $slip->bank,
                    'tfDate'        => date('d M Y', strtotime($slip->transfer_date)),
                    'inputBy'       => get_user_by('ID', $slip->user_id)->data->display_name,
                    'createdDate'   => date('d M Y H:i:s', strtotime($slip->created_date)),
                    'note'          => ''
                );
            }
        }
        $payload['dbg'] = $this->dbg;
        return $payload;
    }

    /**
     * used to querying metadata
     * 
     * @param string $unique invoice for donate, id for slip
     * @param string $mode choose 'order' or 'slip'
     */
    private function _t_output($unique, $mode) {
        global $wpdb;

        if($mode === 'order') {
            $query = "SELECT d.*, c.title, c.abbvr
            FROM $this->table_donate as d
            LEFT JOIN $this->table_campaign as c
            ON d.campaign_id = c.campaign_id
            WHERE d.invoice_id='$unique'";

            $row = $wpdb->get_row($query);
            return $row;
        }
        else if($mode === 'slip') {
            $query = "SELECT s.*, d.name
            FROM $this->table_slip as s
            LEFT JOIN $this->table_donor as d
            ON s.whatsapp = d.whatsapp
            WHERE s.id='$unique'";
            $this->dbg = $query;

            $row = $wpdb->get_row($query);
            return $row;
        }
    }

    /**
     * function to search time followup
     * @param string $invoice invoice
     */
    private function __t_output($invoice) {
        global $wpdb;
        $query = "SELECT f.invoice_id, f.date_fol_up, d.created_at, TIMEDIFF(f.date_fol_up, d.created_at) as selisih
        FROM $this->table_followup as f
        LEFT JOIN $this->table_donate as d
        ON f.invoice_id = d.invoice_id
        WHERE f.invoice_id = '$invoice'";

        $fwp = $wpdb->get_row($query)->selisih;

        return $fwp;
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