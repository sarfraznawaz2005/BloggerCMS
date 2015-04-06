<?php

/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 3/30/2015
 * Time: 4:58 PM
 */
interface Crud
{
    public function get();

    public function add();

    public function edit();

    public function update();

    public function remove();
}