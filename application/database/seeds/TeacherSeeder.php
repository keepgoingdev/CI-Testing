<?php
class TeacherSeeder extends Seeder
{
    private $table = 'tbl_teacher';
    public function run()
    {
        $this->db->truncate($this->table);
        $this->db->query("ALTER TABLE ".$this->table." AUTO_INCREMENT = 1");
    }
}
