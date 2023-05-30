<?php
class Input_leads {
    private $status;
    private $payload;
    private $table_donors;
    private $table_leads;
    private $table_duta;

    private $new_order;
    private $no_leads_ar;
    private $no_leads_obj;
    private $done_leads;
    private $dbg;

    /**
     * @param string $a Mode operation | 'get' for retreive a list, 'submit' for submit no_leads number
     */
    public function __construct($a) {
        global $wpdb;
        $this->table_donors = $wpdb->prefix . 'josh_donors';
        $this->table_leads = $wpdb->prefix . 'josh_leads_log';
        $this->table_duta = $wpdb->prefix . 'josh_duta';

        switch ($a) {
            case 'get':
                $this->_get();
                break;
            case 'submit':
                $this->_submit();
                break;
            default:
                $this->status = false;
                break;
        }
    }

    private function _get() {
        $time = $_POST['date'];
        global $wpdb;

        /**
         * Get CS code
         */
        {
            $user_id = wp_get_current_user()->ID;
            // $user_id = 10;

            $query = "SELECT code
            FROM $this->table_duta
            WHERE user_id='$user_id'";
            $cs_code = $wpdb->get_row($query)->code;
        }
    
        /**
         * Get donate data
         * used by phone picker
         */
        {
            $query = "SELECT id, whatsapp as number, name
            FROM $this->table_donors
            WHERE (DATE_FORMAT(since, '%Y-%m-%d') = '$time')
            AND (owned_by='$cs_code')
            ORDER BY since ASC";
            $new_order = $wpdb->get_results($query);

            $new_order_ = array();
            foreach($new_order as $val) {
                $new_order_[] = array(
                    'id'        => $val->id,
                    'text'      => "$val->number - $val->name",
                    'number'    => $val->number
                );
            }
            $this->new_order = $new_order_;
        }
        
        /**
         * Get no leads data
         */
        {
            $query = "SELECT id, whatsapp as text
            FROM $this->table_donors
            WHERE (remove_reason = 'no_leads')
            AND (DATE_FORMAT(since, '%Y-%m-%d') = '$time')
            AND (owned_by='$cs_code')
            ORDER BY since ASC";
            $rows = $wpdb->get_results($query);
        
            $no_leads_ar = [];
            foreach( $rows as $val ) {
                $no_leads_ar[] = $val->id;
            }
        
            $this->no_leads_ar = $no_leads_ar;
            $this->no_leads_obj = $rows;
        }
    
        /**
         * Get this input leads in this day were done before
         * DONE OR NOT
         */
        {
            $query = "SELECT id
            FROM $this->table_leads
            WHERE (cs_id='$user_id')
            AND (date='$time')
            ORDER BY id DESC";
            $row = $wpdb->get_row( $query );
        
            $this->done_leads = ( $row === null ) ? false : true;
        }
    }
    
    public function get_output() {
        if($this->status === false) {
            return array(
                'status'    => 'failed'
            );
        } else {
            return array(
                'status'        => 'success',
                'phone_list'    => $this->new_order,
                'no_leads_ar'   => $this->no_leads_ar,
                'no_leads_obj'  => $this->no_leads_obj,
                'done_leads'    => $this->done_leads,
            );
        }
    }

    private function _submit() {
        $date = $_POST['date'];
        $noWa = $_POST['phone'];
        global $wpdb;

        /**
         * Get User ID CS and CS Code
         */
        {
            $user_id = wp_get_current_user()->ID;
            // $user_id = 10;
            $cs_name = get_user_meta( $user_id, 'first_name', true);

            $query = "SELECT code
            FROM $this->table_duta
            WHERE user_id='$user_id'";
            $cs_code = $wpdb->get_row($query)->code;
        }
        
        /**
         * Convert noWa data into clean escapes and array format
         */
        {
            if( $noWa === null ) {
                $noWa_ar = array();
            } else {
                $noWa = stripslashes($noWa);
                $noWa_ar = json_decode( $noWa );

                $noWa_ar_sc = array();
                foreach($noWa_ar as $val) {
                    $noWa_ar_sc[] = $val->text;
                }
            }
        }

        /**
         * Submit to leads_log
         */
        {
            $insert_leads = $wpdb->insert(
                $this->table_leads,
                array(
                    'cs_id'		=> $user_id,
                    'cs_name'	=> $cs_name,
                    'no_leads'  => $noWa,
                    'date'		=> $date
                )
            );
    
            if( $insert_leads === false ) {
                $error_db = $wpdb->last_error;

                $this->status = false;
                $this->payload = array(
                    'message'   => "Insert database failed! $error_db"
                );
    
                return;
            }
        }

        /**
         * Get all new phone number
         */
        {
            $query = "SELECT id, whatsapp
            FROM $this->table_donors
            WHERE (DATE_FORMAT(since, '%Y-%m-%d') = '$date')
            AND (owned_by='$cs_code')
            ORDER BY since ASC";
            $new_order = $wpdb->get_results($query);
            $this->dbg = $noWa_ar_sc;

            $new_order_ar = array();
            foreach($new_order as $val) {
                $new_order_ar[] = $val->whatsapp;
            }
        }

        /**
         * Update data in Donors
         */
        {
            $remove_by = json_encode(array(
                'id'    => $user_id,
                'name'  => $cs_name
            ));
            $_date = date('Y-m-d H:i:s');
    
            // foreach( $noWa_ar as $val ) {
            foreach( $new_order as $val ) {
                if(in_array($val->whatsapp, $noWa_ar_sc)) {
                    $remove = 1;
                    $remove_reason = 'no_leads';
                    $remove_dt = $_date;
                    $remove_by_ = $remove_by;
                } else {
                    $remove = null;
                    $remove_reason = null;
                    $remove_dt = null;
                    $remove_by_ = null;
                }
    
                $update_donors = $wpdb->update(
                    $this->table_donors,
                    array(
                        'remove'        => $remove,
                        'remove_reason' => $remove_reason,
                        'remove_dt'     => $remove_dt,
                        'remove_by'     => $remove_by_
                    ),
                    array(
                        'id'            => $val->id
                    )
                );

            }
        }

    }

    public function submit_output() {
        if($this->status === false) {
            return array(
                'status'    => 'failed',
                'messages'  => $this->payload
            );
        } else {
            return array(
                'status'    => 'success',
                'dbg'       => $this->dbg
            );
        }
    }
}