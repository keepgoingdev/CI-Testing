<?php
class Course_controller_test extends TestCase
{
    public function test_When_you_search_course_name_Then_check_results()
    {
        
        $ajax_data = 
        [
            'search' =>
            [
                'value' => 'DATA',
                'regex' => false
            ]
        ];
        $output_str = $this->ajaxRequest('POST', 'Course/get_courses_ajax', $ajax_data);        
        
        $this->assertResponseCode(200);

        $output = json_decode($output_str);
        $this->assertEquals($output->recordsFiltered, 2);

        $this->assertEquals(count($output->data), 2);
	}
}
?>