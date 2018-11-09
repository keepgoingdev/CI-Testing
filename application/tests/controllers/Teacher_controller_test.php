<?php
class Teacher_controller_test extends TestCase
{
    public function test_When_you_search_teacher_name_Then_check_results()
    {
        $search_str = 'Lars Åke Svensson';      
        $ajax_data = 
        [
            'search' =>
            [
                'value' => $search_str,
                'regex' => false
            ]
        ];
        $output_str = $this->ajaxRequest('POST', 'Teacher/get_teachers_ajax', $ajax_data);        
        
        $this->assertResponseCode(200);

        $output = json_decode($output_str);
        $this->assertEquals($output->recordsFiltered, 1);

        $this->assertEquals(count($output->data), 1);
        
        $this->assertEquals($output->data[0]->full_name, $search_str);
	}
}
?>