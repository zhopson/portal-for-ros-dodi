<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lib;

/**
 * Description of GlobalVars
 *
 * @author andrey-man
 */
class GlobalVars {
    
    private $gcdr;
    
    public function __construct() {
        $this->gcdr = '';
    }
    
    public function SetGCDR($pcdr) {
        $this->gcdr = $pcdr;
    }
    
    public function GetGCDR() {
        return $this->gcdr;
    }
    
}
