<?php
class CourseEventSeeder extends Seeder
{
    private $table_course_event = 'tbl_course_event';
    private $table_course_event_teachers = 'tbl_course_event_teachers';
    public function run()
    {
        $this->db->truncate($this->table_course_event);
        $this->db->query("ALTER TABLE ".$this->table_course_event." AUTO_INCREMENT = 1");

        $this->db->truncate($this->table_course_event_teachers);
        $this->db->query("ALTER TABLE ".$this->table_course_event_teachers." AUTO_INCREMENT = 1");
    }
}
