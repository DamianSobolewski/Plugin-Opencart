<?php

class ModelExtensionPaymentHomepay extends Model{

    public function createDatabaseTables(){
        $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "homepay_so` (
            `order_id` int(32) NOT NULL,
            `session_id` varchar(32) NOT NULL,
            `status` varchar(32)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        $this->db->query($sql);
    }

    public function dropDatabaseTables(){
        $sql = "DROP TABLE IF EXISTS `" . DB_PREFIX . "homepay_so`;";
        $this->db->query($sql);
    }
}
