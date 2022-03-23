<?php

interface Template{
    public function create($data);
    public function read_all();
    public function read_one($data);
    public function update($data);
    public function delete($data);
}