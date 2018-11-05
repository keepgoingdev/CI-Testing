<?php defined('BASEPATH') OR exit('No direct script access allowed');
$controller_name = $this->router->class;

$top_name = $this->ion_auth->user()->row()->first_name;
$top_email = $this->ion_auth->user()->row()->email;
?><body class="page-header-fixed page-sidebar-closed-hide-logo">
    <div class="wrapper">
        <header class="page-header">
            <nav class="navbar mega-menu">
                <div class="container-fluid">
                    <div class="clearfix navbar-fixed-top">

                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive- collapse">
                            <span class="sr-only">Ändra navigering</span>
                            <span class="toggle-icon">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </span>
                        </button>

                        <a id="index" class="page-logo" href="<?php echo site_url(); ?>">
                            <img src="<?php echo base_url('assets/apps/img/app_logo.png') ; ?>" alt="Logo">
                        </a>

                        <form class="search" id="system_search_wrapper">
                            <input id="system_search" type="text" class="form-control" name="query" placeholder="Sök..." value="<?php if (isset($query)) { echo $query; } ?>">
                            <a href="#" id="system_search_btn" class="btn submit md-skip">
                                <i class="fa fa-search"></i>
                            </a>
                        </form>

                        <div class="topbar-actions">
                            <div class="btn-group-purple btn-group">
                                <a href="#" class="btn btn-sm md-skip" data-toggle="modal" data-target="#faqModal" title="FAQ">
                                    <i class="fa fa-question" style="line-height:1.2;"></i>
                                </a>
                            </div>

                            <?php
                            if ($this->auth == 'super_admin' || $this->auth == 'admin' || $this->auth == 'user')
                            {
                            ?>
                            <div class="btn-group-turquoise btn-group">
                                <button type="button" id="generate_reports" class="btn btn-sm md-skip dropdown-toggle" title="Skapa rapporter">
                                    <i class="fa fa-bar-chart"></i>
                                </button>
                            </div>

                            <div class="btn-group-notification btn-group" id="header_notification_bar">
                                <button type="button" class="btn btn-sm md-skip dropdown-toggle" data-toggle="dropdown" title="Meddelanden">
                                    <?php 
                                        if ($messages_count > 0)
                                        {
                                            echo '<i class="fa fa-envelope"></i>';
                                        }
                                        else 
                                        {
                                            echo '<i class="fa fa-envelope-open"></i>';
                                        }
                                    ?>
                                    <span class="badge">
                                        <?php echo $messages_count; ?>
                                    </span>
                                </button>

                                <ul class="dropdown-menu-v2">
                                    <li class="external">
                                        <h3><span class="bold"><?php echo $messages_count; ?></span> meddelanden</h3>
                                        <a href="<?php echo site_url('messages'); ?>">visa alla</a>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list scroller" style="height: 250px; padding: 0;" data-handle-color="#637283">
                                            <?php
                                if (isset($messages))
                                {
                                    if (is_array($messages))
                                    {
                                        foreach ($messages as $me)
                                        {
                                            echo '<li>
                                                                <a href="'.site_url('messages').'">
                                                                    <span class="details">
                                                                        <span class="label label-sm label-icon label-success md-skip">
                                                                            <i class="'.$me->icon.'"></i>
                                                                        </span> 
                                                                        '.$me->title.'
                                                                    </span>
                                                                    <span class="time">'.$me->date.'</span>
                                                                </a>
                                                            </li>';
                                        }
                                    }
                                }
                                            ?>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                            <div class="btn-group-red btn-group">
                                <button type="button" class="btn btn-sm md-skip dropdown-toggle" data-toggle="dropdown" title="Skapa">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <ul class="dropdown-menu-v2" role="menu">
                                    <li>
                                        <a href="<?php echo site_url('teacher/new_teacher'); ?>">Skapa utbildare</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo site_url('customers/new_customer'); ?>">Skapa företag</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo site_url('course/new_course'); ?>">Skapa utbildning</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo site_url('course_event/new_course_event'); ?>">Skapa utbildningstillfälle</a>
                                    </li>
                                </ul>
                            </div>

                            <?php
                            }
                            ?>

                            <div class="btn-group-img btn-group">
                                <button type="button" class="btn btn-sm md-skip dropdown-toggle" data-toggle="dropdown">
                                    <span>Hej, <?php echo $top_name; ?></span>
                                    <?php
                                    echo gravatar($top_email, 32, 'x', false, 'monsterid');
                                    ?>
                                </button>
                                <ul class="dropdown-menu-v2" role="menu">
                                    <?php
                                    if ($this->auth == 'super_admin') {
                                        echo '<li>
                                                    <a href="'.base_url('settings').'">
                                                        <i class="fa fa-cogs"></i> Inställningar
                                                    </a>
                                                </li>';
                                    }
                                    ?>

                                    <li>
                                        <a href="<?php echo base_url('login/logout'); ?>">
                                            <i class="fa fa-sign-out"></i> Logga ut
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <a class="btn btn-sm quick-sidebar-toggler md-skip" href="<?php echo base_url('login/logout'); ?>">
                                <span class="sr-only">Logga ut</span>
                                <i class="icon-logout"></i>
                            </a>

                        </div>
                    </div>                        

                    <div class="nav-collapse collapse navbar-collapse navbar-responsive-collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown dropdown-fw<?php if($controller_name == 'login') { echo ' active open selected'; } ?>" >
                                <a href="<?php echo site_url('dashboard'); ?>" class="text-uppercase">
                                    <i class="fa fa-tachometer"></i> Översikt
                                </a>
                            </li>
                            <?php
                            if ($this->auth == 'super_admin' || $this->auth == 'admin' || $this->auth == 'user')
                            {
                            ?>
                            <li class="dropdown dropdown-fw<?php if($controller_name == 'customers') { echo ' active open selected';  } ?> ">
                                <a href="<?php echo site_url('customers'); ?>" class="text-uppercase">
                                    <i class="fa fa-building"></i> Företag
                                </a>
                            </li>
                            <li class="dropdown dropdown-fw<?php if($controller_name == 'participant') { echo ' active open selected';  } ?> ">
                                <a href="<?php echo site_url('participant'); ?>" class="text-uppercase">
                                    <i class="fa fa-users"></i> Deltagare
                                </a>
                            </li>
                            <li class="dropdown dropdown-fw<?php if($controller_name == 'course') { echo ' active open selected';  } ?> ">
                                <a href="<?php echo site_url('course'); ?>" class="text-uppercase">
                                    <i class="fa fa-tags"></i> Utbildningar
                                </a>
                            </li>
                            <li class="dropdown dropdown-fw<?php if($controller_name == 'teacher') { echo ' active open selected'; } ?> ">
                                <a href="<?php echo site_url('teacher'); ?>" class="text-uppercase">
                                    <i class="fa fa-briefcase"></i> Utbildare
                                </a>
                            </li>
                            <li class="dropdown dropdown-fw<?php if($controller_name == 'course_event') { echo ' active open selected';  } ?> ">
                                <a href="<?php echo site_url('course_event'); ?>" class="text-uppercase">
                                    <i class="fa fa-calendar-o"></i> Utbildningstillfällen
                                </a>
                            </li>
                            <?php
                            }
                            ?>
                            <?php
                            if ($this->auth == 'extended_teacher')
                            {
                            ?>
                            <li class="dropdown dropdown-fw<?php if($controller_name == 'customers') { echo ' active open selected';  } ?> ">
                                <a href="<?php echo site_url('customers'); ?>" class="text-uppercase">
                                    <i class="fa fa-building"></i> Företag
                                </a>
                            </li>
                            <li class="dropdown dropdown-fw<?php if($controller_name == 'participant') { echo ' active open selected';  } ?> ">
                                <a href="<?php echo site_url('participant'); ?>" class="text-uppercase">
                                    <i class="fa fa-users"></i> Deltagare
                                </a>
                            </li>
                            <li class="dropdown dropdown-fw<?php if($controller_name == 'course_event') { echo ' active open selected';  } ?> ">
                                <a href="<?php echo site_url('course_event'); ?>" class="text-uppercase">
                                    <i class="fa fa-calendar-o"></i> Utbildningstillfällen
                                </a>
                            </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>

                </div>
            </nav>
        </header>