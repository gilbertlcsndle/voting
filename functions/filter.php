<?php
// sets the session for filtering
class filter {
    private $_active_page;
    /**
      * @param data array
      */
    public function __construct($active_page) {
        $this->_active_page = $active_page;
    }
    public function set_filter($data) {
        foreach ($data as $key => $value) {
            $_SESSION[$this->_active_page][$key] = $value;
        }
        header('location:'.$this->_active_page);
    }
    /**
      * @param filter_keys array/string
      */
    public function unset_filter($filter_key) {
        if (count($filter_key) > 1) {
            foreach($filter_key as &$value) {
                unset($_SESSION[$this->_active_page][$value]);
            }
        } else {
            unset($_SESSION[$this->_active_page][$filter_key]);
        }
        header('location:'.$this->_active_page);
    }
}
?>