<?php
class TestCase extends CIPHPUnitTestCase
{
    protected static $is_test_db_filled;
    public static function setUpBeforeClass()
    {
        //Login
        $CI =& get_instance();
        $CI->load->library('ion_auth');
        $CI->ion_auth->login('admin@suu.com', 'password', '1');
    }

    public function setUp()
    {
        $this->fill_Test_Database();
    }

    protected function fill_Test_Database()
    {
        //Execute this function only one time
        if(self::$is_test_db_filled)
            return;
        self::$is_test_db_filled = true;

        $CI =& get_instance();
        $CI->load->library('Seeder');

        //Call Seeders
        $CI->seeder->call('CourseSeeder');
        $CI->seeder->call('TeacherSeeder');
        $CI->seeder->call('CourseEventSeeder');

        //Load Models
        $this->resetInstance();
        $this->CI->load->model('course_model');
        $this->CI->load->model('teacher_model');
        $this->CI->load->model('course_event_model');
        
        //Insert course data
        $test_course_data = array(
            array(
                'course_name'           => "My Test Course",
                'course_description'    => 'Software Development Course',
                'course_time_from'      => '05:55:00',
                'course_time_end'       => '07:55:00',
                'course_time'           => '2 timmar',
                'maximum_participants'  => '3',
                'course_external_price' => '2300'
            ),
            array(
                'course_name'           => 'Utbildning',
                'course_description'    => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio.',
                'course_time_from'      => '10:56:36',
                'course_time_end'       => '10:59:04',
                'course_time'           => '1 dag 2 timmar',
                'maximum_participants'  => '6',
                'course_external_price' => '1899'
            ),
            array(
                'course_name'           => 'Ny testutibldning 20181019',
                'course_description'    => 'blabla',
                'course_time_from'      => '07:00:00',
                'course_time_end'       => '17:00:00',
                'course_time'           => '1 dag',
                'maximum_participants'  => '12',
                'course_external_price' => '2800'
            ),
            array(
                'course_name'           => 'UTB I DATA',
                'course_description'    => 'ah',
                'course_time_from'      => '18:12:00',
                'course_time_end'       => '18:12:00',
                'course_time'           => '1 dag',
                'maximum_participants'  => '19',
                'course_external_price' => '2300'
            ),
            array(
                'course_name'           => 'UTB DATA B',
                'course_description'    => 'Bla bla',
                'course_time_from'      => '12:12:00',
                'course_time_end'       => '14:12:00',
                'course_time'           => '2 timmar',
                'maximum_participants'  => '3',
                'course_external_price' => '3200'
            )
        );
        foreach( $test_course_data as $data )
            $this->CI->course_model->save($data);

        //Insert Teacher data
        $test_teacher_data = array(
            array(
                'user_id'       => 1,
                'first_name'    => 'Natalia',
                'last_name'     => 'Comracova',
                'address'       => 'Vladivostok',
                'email'         => 'natalia@email.com',
                'phone'         => '1928-3729102',
                'courses'       => '1,2,3'
            ),
            array(
                'user_id'       => 2,
                'first_name'    => 'James',
                'last_name'     => 'Wilson',
                'address'       => 'Roslagstullsbacken a4, Stockholm',
                'email'         => 'wilson@email.com',
                'phone'         => '1232-123212321232',
                'courses'       => '2,4'
            ),
            array(
                'user_id'       => 3,
                'first_name'    => 'Tomson',
                'last_name'     => 'Tellaer',
                'address'       => 'Hälsingegatan a6, Stockholm',
                'email'         => 'kals@email.com',
                'phone'         => '1234-3214322344',
                'courses'       => '4,5'
            ),
            array(
                'user_id'       => 4,
                'first_name'    => 'Jan-Patrik',
                'last_name'     => 'Svensson Larsson',
                'address'       => 'Knutsgatan 3, Västerås',
                'email'         => 'jan.patriksvensson@knutsgatan.se',
                'phone'         => '0401-21212',
                'courses'       => '1,2,3,5'
            ),
            array(
                'user_id'       => 5,
                'first_name'    => 'Lars Åke',
                'last_name'     => 'Svensson',
                'address'       => 'Tegnérgatan, Stockholm',
                'email'         => 'anton.svensson@testforetaget.se',
                'phone'         => '0707-33333',
                'courses'       => '4,5'
            )
        );
        foreach( $test_teacher_data as $data )
            $this->CI->teacher_model->insert($data);

        //Insert Course Event Data
        $test_course_event_data = array(
            array(
                'data'      => array(
                        'user_id'           => 1,
                        'course_id'         => 1,
                        'course_code'       => '20161020FALLSVÄXJÖ',
                        'location'          => 'Lantmannagatan 93, 243 59 Höör',
                        'city'              => 'Höör',
                    ),
                'teachers'  => array(1, 2)
            ),
            array(
                'data'      => array(
                        'user_id'           => 2,
                        'course_id'         => 2,
                        'course_code'       => '20161024HETAAJÖNKÖ',
                        'location'          => 'Maria Prästgårdsgata a1',
                        'city'              => 'Stockholm',
                    ),
                'teachers'  => array(3)
            ),
            array(
                'data'      => array(
                        'user_id'           => 3,
                        'course_id'         => 3,
                        'course_code'       => '20171121YKB STOCPB1',
                        'location'          => 'Skeppsbron',
                        'city'              => 'Höör',
                    ),
                'teachers'  => array(3,5)
            ),
            array(
                'data'      => array(
                        'user_id'           => 4,
                        'course_id'         => 4,
                        'course_code'       => '20171030BYGGKLIPGA1',
                        'location'          => '53 Slytan Stavstuguängen',
                        'city'              => 'Eskilstuna',
                    ),
                'teachers'  => array(2,4)
            ),
            array(
                'data'      => array(
                        'user_id'           => 5,
                        'course_id'         => 5,
                        'course_code'       => '20171030SKOTTUMBTG1',
                        'location'          => 'Wollmar Yxkullsgatan',
                        'city'              => 'Stockholm',
                    ),
                'teachers'  => array(5)
            )
        );
        foreach( $test_course_event_data as $course_event )
        {
            $insert_id = $this->CI->course_event_model->insert($course_event['data']);
            //Insert Teachers
            $this->CI->course_event_model->insert_teacher($course_event['teachers'] , $insert_id);
        }
    }
}
