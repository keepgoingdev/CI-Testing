<?php
class CourseSeeder extends Seeder
{
    private $table = 'tbl_course';
    public function run()
    {
        $this->db->truncate($this->table);
        $this->db->query("ALTER TABLE ".$this->table." AUTO_INCREMENT = 1");
    }
}
