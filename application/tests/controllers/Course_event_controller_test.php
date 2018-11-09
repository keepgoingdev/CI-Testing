<?php
class Course_event_controller_test extends TestCase
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
        $output_str = $this->ajaxRequest('POST', 'Course_event/get_course_event_ajax', $ajax_data);        
        
        $this->assertResponseCode(200);

        $output = json_decode($output_str);
        $this->assertEquals($output->recordsFiltered, 2);

        $this->assertEquals(count($output->data), 2);
    }
    
    public function test_When_you_search_city_Then_check_results()
    {
        $search_str = 'Höör';      
        $ajax_data = 
        [
            'search' =>
            [
                'value' => $search_str,
                'regex' => false
            ]
        ];
        $output_str = $this->ajaxRequest('POST', 'Course_event/get_course_event_ajax', $ajax_data);        
        
        $this->assertResponseCode(200);

        $output = json_decode($output_str);
        $this->assertEquals($output->recordsFiltered, 2);

        $this->assertEquals(count($output->data), 2);
    }
    
    public function test_When_you_search_course_code_Then_check_results()
    {
        $search_str = '20161024HETAAJÖNKÖ';      
        $ajax_data = 
        [
            'search' =>
            [
                'value' => $search_str,
                'regex' => false
            ]
        ];
        $output_str = $this->ajaxRequest('POST', 'Course_event/get_course_event_ajax', $ajax_data);        
        
        $this->assertResponseCode(200);

        $output = json_decode($output_str);
        $this->assertEquals($output->recordsFiltered, 1);

        $this->assertEquals(count($output->data), 1);
	}
}
?>