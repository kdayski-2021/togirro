<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Design_lib 
{

    public function __construct($options = array())
    {
        $this->CI = &get_instance();
        $this->CI->load->library(array('h_lib', 'parser'));

        $this->CI->html            = '';
        $this->templates_path      = 'assets/templates/'; // Директория шаблонов
        $this->template            = 'teacher2020'; // Шаблон по умолчанию
        $this->template_clear_list = array('email', 'clear');

        $this->head_files = '';

        $this->page_title_default = '';

        // Текущий контроллер
        $this->controller = $this->CI->uri->segment(1);

        // CSS files
        $this->css_files = array();

        // JS files
        $this->version  = 33;
        $this->js_files = array(
            // Core
            'main/js/core/jquery.min.js?3.3.1',
            'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js',
            'main/js/core/bootstrap.min.js?4.1.0',
            // Plugins
            'main/plugins/bootstrap.select/bootstrap.select.js?1.12.4.c.3',
            'main/plugins/debounce/jquery.debounce-1.0.5.js',
            'main/plugins/highlight/jquery.highlight.js?1.06',
            'main/plugins/holder/holder.min.js?2.9.4',
            'main/plugins/fontawesome/fontawesome-all.min.js?5.8.1',
            'main/plugins/noty/noty.min.js?3.2.0.b',
            'main/plugins/session/jquery.session.js',
            'main/plugins/slick/slick.min.js?1.8.0',
            'main/plugins/TimeCircles/TimeCircles.js?0.1',
            // Project
            'main/js/core/utilities.js?3',
            'main/js/core/variables.js?2',
            'main/js/core/core.js?5',
            'main/js/auth.js?5',
            'main/js/lang.js?1',
            'main/js/subscribe.js?1',
            'main/js/timer.js?3',
            'main/js/invest.js?13',
            // Custom
            'main/js/blockchain.js?3',
        );

        // Template head files
        $this->template_head_files = array(
            'admin'   => array(
                'css' => array(
                    'admin/css/admin.min.css?4',
                    'admin/css/store.css?4',
                ),
                'js'  => array(
                    'admin/js/admin.js?2',
                ),
            ),
            'airdrop' => array(
                'css' => array(
                    'welcome/css/welcome.min.css?11',
                    'airdrop/css/airdrop.min.css?7',
                ),
                'js'  => array(
                    'airdrop/js/airdrop.js?8',
                ),
            ),
            'auth'    => array(
                'css' => array(
                    'auth/css/auth.min.css?3',
                ),
            ),
            'error'   => array(
                'css' => array(
                    'error/css/error.min.css?2',
                ),
                'js'  => array(
                    'main/plugins/particleground/jquery.particleground.min.js?1.1.0',
                ),
            ),
            'lk'      => array(
                'css' => array(
                    'lk/css/lk.min.css?4',
                )
            ),
            'msg'     => array(
                'css' => array(
                    'msg/css/msg.min.css?1',
                ),
            ),
            'portal'  => array(
                'css' => array(
                    //'https://use.fontawesome.com/releases/v5.8.1/css/all.css',
                    'portal/plugins/bootstrap/css/bootstrap.min.css?4.3.1',
                    'portal/css/essentials.css',
                    'portal/css/layout.css',
                    'portal/css/header-1.css',
                    'portal/css/color_scheme/green.css',
                ),
                'js'  => array(),
            ),
            'teacher2020' => array(
                'css'               => array(
                    'teacher2020/css/fontawesome.min.css',
					'teacher2020/css/themify-icon.css',
					'teacher2020/css/plugins.css',
					'teacher2020/css/bootstrap-multiselect.css',
					'teacher2020/css/tilda-blocks-2.12.css',
					'teacher2020/css/tilda-grid-3.0.min.css',
					'teacher2020/css/style.css?' . $this->version,
					
					'teacher2020/css/map.css?' . $this->version,
					'teacher2020/css/roadmap.css?' . $this->version,
					'teacher2020/css/roadmap2.css?' . $this->version,   
					'teacher2020/css/v.css?' . $this->version,
					'teacher2020/css/register.css?' . $this->version,
                ),
                'js'                => array(
					'teacher2020/js/jquery.min.js',
					'teacher2020/js/popper.min.js',
					'teacher2020/js/bootstrap.min.js',            
					'teacher2020/js/bootstrap-multiselect.js',  					
					'teacher2020/js/aos.js',
					'teacher2020/js/navscroll.min.js',
					'teacher2020/js/owl.carousel.min.js',
					'teacher2020/js/magnific-popup.js',
					'teacher2020/js/youtube-video.js',
					'teacher2020/js/SyoTimer.js',
					'teacher2020/js/plugins.js',
					
					'teacher2020/js/main.js?' . $this->version,
					'teacher2020/js/map.js?' . $this->version,
					'teacher2020/js/fan.js?' . $this->version,
					'teacher2020/js/fan.institution.js?' . $this->version,
					'teacher2020/js/roadmap.js?' . $this->version,
                ),
                'load_default_head' => false,
            ),
            'widget'  => array(
                'css' => array(
                    'admin/css/admin.min.css?16',
                ),
            ),
        );

        $this->load_default_head = false;

        $this->head();

        // Debug
        $this->debug = $this->CI->config->item('debug_mode');
        if (!$this->CI->login) {
            $this->debug = false;
        }
        $this->show_debug = isset($options['debug']) && $options['debug'] === false ? false : true;
    }

    public function set_template($template = '')
    {
        if (is_dir($this->templates_path . $template)) {
            $this->template = $template;
        }
    }

    public function head($files = array(), $out = '')
    {
        $js_files = $css_files = array();

        if (!is_array($files)) {
            $files = array($files);
        }

        if (!$this->load_default_head) {
            if (!isset($this->template_head_files[$this->template]['load_default_head'])) {

                $this->load_default_head = true;

                foreach ($this->css_files as $file) {
                    $path = (strpos($file, 'http') === false) ? $this->templates_path : '';

                    $css_files[] = "{$path}$file";
                }

                foreach ($this->js_files as $file) {
                    $path = (strpos($file, 'http') === false) ? $this->templates_path : '';

                    $js_files[] = "{$path}$file";
                }
            }
        }

        foreach ($files as $file) {
            // У файла может быть версионно, по этому разбираем строку
            $file_parts = explode('?', $file);

            if (isset($file_parts[1])) {
                $file_version = '?' . $file_parts[1];
                $file         = $file_parts[0];
            } else {
                $file_version = '';
            }

            // Отделаяем формат файла
            $file_parts  = explode('.', $file_parts[0]);
            $file_format = strtolower($file_parts[count($file_parts) - 1]);

            switch ($file_format) {
                case 'css':
                    $css_files[] = $this->templates_path . $file . $file_version;
                    break;

                case 'js':
                    $js_files[] = $this->templates_path . $file . $file_version;
                    break;

                default:
                    echo '<script>alert("Error: undefined type of file. Not push in head")</script>';
            }
        }

        foreach ($css_files as $item) {
            $out .= "<link rel=stylesheet href=$item type=text/css />";
        }

        foreach ($js_files as $item) {
            $out .= "<script src=$item ></script>";
        }

        $this->head_files .= $out;
    }

    public function msg($options = array())
    {
        $this->set_template('msg');

        $options['title'] = isset($options['title']) ? $options['title'] : 'Message';

        $options['url']      = isset($options['url']) ? $options['url'] : '#';
        $redirect            = isset($options['redirect']) && $options['redirect'] === false ? false : true;
        $options['external'] = isset($options['external']) && $options['external'] ? ' rel="external"' : '';

        $link             = isset($options['link']) ? $options['link'] : array();
        $options['links'] = array(
            array(
                'id'      => 'continue',
                'content' => isset($link['content']) ? $link['content'] : 'Continue',
                'url'     => isset($link['url']) ? $link['url'] : $options['url'],
                'theme'   => 'primary',
                'attr'    => array(
                    $options['external'],
                    'redirect' => $redirect,
                ),
            ),
        );

        $data['msg'] = $this->CI->h_lib->jumbotron($options);

        $this->show('system/msg', $data);
        exit;
    }

    public function show($_parse_template = '', $_data = array(), $options = true)
    {
        $project_logo = $this->CI->config->item('project_logo');
        $project_name = $this->CI->config->item('project_name');
        $copyright    = $this->CI->lang_lib->lang_data['system_copyright'];
        $social       = $this->CI->social_lib->get();
        $lang_pref    = $this->CI->lang_lib->user_lang;

        if (is_array($options)) {
            $output = true;
        } else {
            $output = $options;
        }

        // Ничего не выводим в браузер
        if (!$output) {
            ob_start();
        }

        // Подключение шаблонных JS, CSS
        foreach (array('css', 'js') as $file_type) {
            if (isset($this->template_head_files[$this->template][$file_type]) && is_array(
                    $this->template_head_files[$this->template][$file_type]
                )) {
                $this->head($this->template_head_files[$this->template][$file_type]);
            }
        }

        // Базовые JS-переменные
        $core_scripts = implode(
            '',
            array(
                "<script>var siteUrl = '" . site_url() . "'</script>",
                "<script>var current_unix_time = " . time() . "</script>",
                "<script>var projectName = '" . $this->CI->config->item('project_name') . "'</script>",
            )
        );

        // Базовые JS-файлы
        $core_head_files   = $this->CI->parser->parse('system/core/head_files', array(), true);
        $core_body_files   = $this->CI->parser->parse('system/core/body_files', array(), true);
        $core_footer_files = $this->CI->parser->parse(
            'system/core/footer_files',
            array(
                'loader' => $this->CI->parser->parse('system/loader', array(), true),
            ),
            true
        );

        $head_files = $this->head_files;

        // Тех. поддержка
        $jivosite = $this->CI->parser->parse('system/jivosite', array(), true);

        // Template
        $head_title = $project_name;
        $topbar     = ''; // Информация над шапкой
        $header     = ''; // Шапка
        $navbar     = array(); // Меню в шапке
        $sidebar    = ''; // Левое меню
        $asidebar   = ''; // Правое меню
        $admin      = array();

        // Языки
        $languages = $this->CI->parser->parse(
            'system/languages',
            array(
                'languages' => $this->CI->lang_lib->languages_full,
            ),
            true
        );


        if ($this->template == 'airdrop') {
            $head_title = 'Official airdrop ' . $project_name;
        }

        // Check Authorization
        if (!$this->CI->login) {
            // Если пользователь НЕ авторизирован
        } else {
            // Если пользователь авторизирован

            switch ($this->template) {
                case 'lk':
                    // Header
                    if ($this->CI->manager) {
                        $admin[] = array(
                            'url'     => 'ad',
                            'content' => $this->CI->lang_lib->lang_data['system_header_menu_admin'],
                        );
                    }

                    $header = $this->CI->parser->parse(
                        'system/lk/tpl_header',
                        array(
                            'project_name'     => $project_name,
                            'admin'            => $admin,
                            'login_content'    => $this->CI->login ? $this->CI->lang_lib->lang_data['system_header_menu_log_out'] : $this->CI->lang_lib->lang_data['system_header_menu_log_in'],
                            'login_url'        => $this->CI->login ? 'login/out' : 'login',
                            'language_content' => $this->CI->lang_lib->lang_data['system_header_menu_languages'],
                            'languages'        => $languages,
                        ),
                        true
                    );


                    // Sidebar menu
                    $sidebar_lang = $this->CI->lang_lib->load('sidebar');

                    $sidebar_menu = array(
						array(
                            'group_list_title' => '',
                            'group_list_menu'  => array(
                                array(
                                    'url'     => 'welcome',
                                    'content' => 'Главная страница',
                                )
                            ),
                        ),
                        array(
                            'group_list_title' => '',
                            'group_list_menu'  => array(
                                array(
                                    'url'     => 'vote',
                                    'content' => 'Голосование',
                                )
                            ),
                        ),
						// array(
                        //     'group_list_title' => '«Вебинары, мастер-классы, дискуссии и др»',
                        //     'group_list_menu'  => array(                               
						// 		array(
                        //             'url'     => 'register5',
                        //             'content' => 'Регистрация',
                        //         )
                        //     ),
                        // )
                    );

                    

                    $sidebar = $this->_render_sidebar($sidebar_menu);
                    break;

                case 'admin':
                    $this->CI->load->library(array('ad_lib'));

                    $header = $this->CI->parser->parse(
                        'system/admin/tpl_header',
                        array(
                            'project_name'     => $project_name,
                            'admin'            => array(
                                array(
                                    'url'     => 'lk',
                                    'content' => $this->CI->lang_lib->lang_data['system_header_menu_lk'],
                                ),
                            ),
                            'login_content'    => ($this->CI->login) ? $this->CI->lang_lib->lang_data['system_header_menu_log_out'] : $this->CI->lang_lib->lang_data['system_header_menu_log_in'],
                            'login_url'        => ($this->CI->login) ? 'login/out' : 'login',
                            'language_content' => $this->CI->lang_lib->lang_data['system_header_menu_languages'],
                            'languages'        => $languages,
                        ),
                        true
                    );

                    $sidebar_menu = $this->CI->ad_lib->sidebar_menu;

                    // Sidebar menu
                    $sidebar = $this->_render_sidebar($this->CI->ad_lib->sidebar_menu);
                    break;
            }
        }

        switch ($this->template) {
            case 'portal':
                $portal_lang_data = $this->CI->lang_lib->load('portal', false);

                $menu = array(
                    array(
                        'url'    => 'portal/news',
                        'title'  => $portal_lang_data['portal_menu_news'],
                        'active' => '',
                    ),
                    array(
                        'url'    => 'portal/projects',
                        'title'  => $portal_lang_data['portal_menu_projects'],
                        'active' => '',
                    ),
                    array(
                        'url'    => 'portal/docs',
                        'title'  => $portal_lang_data['portal_menu_docs'],
                        'active' => '',
                    ),
						  /*
                    array(
                        'url'    => 'portal/about',
                        'title'  => $portal_lang_data['portal_menu_about'],
                        'active' => '',
                    ),*/
                    array(
                        'url'    => 'portal/contacts',
                        'title'  => $portal_lang_data['portal_menu_contacts'],
                        'active' => '',
                    ),
                );

                $curUri = $this->CI->uri->uri_string();

                foreach ($menu as $menu_index => $menu_item) {
                    if (strpos($curUri, $menu_item['url']) !== false) {
                        $menu[$menu_index]['active'] = 'active';
                    }
                }

                $header = $this->CI->parser->parse(
                    'system/portal/tpl_header',
                    array(
                        'menu' => $menu,
                    ),
                    true
                );

                $news_q = $this->CI->db->where(array('news_date_publish <=' => date('Y-m-d H:i:s')))
                                       ->limit(3)
                                       ->order_by('news_date_publish', 'DESC')
                                       ->get('news_' . $lang_pref)
                                       ->result_array();

                $latest_news = array();
                foreach ($news_q as $news_item) {
                    $latest_news[] = array(
                        'news_title' => $news_item['news_title'],
                        'news_url'   => "portal/news/{$lang_pref}/{$news_item['news_id']}",
                        'news_date'  => $this->CI->lang_lib->date(
                            array(
                                'date'   => $news_item['news_date_publish'],
                                'format' => 'dmy',
                            )
                        ),
                    );
                }

                $contacts_lang_data = $this->CI->lang_lib->load('portal_contacts', false);

                $company_phone = $this->CI->config->item('project_phone');
                $company_email = $this->CI->config->item('project_email');

                $footer_data = array(
                    'project_name' => $project_name,
                    'copyright'    => "&copy; {$portal_lang_data['portal_footer_copyright']} {$project_name}",
                    'latest_news'  => $latest_news,
                    'menu'         => $menu,
                );

                $footer_data = array_merge($footer_data, $portal_lang_data);
                $footer_data = array_merge($footer_data, $contacts_lang_data);

                $footer_data = array_merge($footer_data, array(
                    'company_address_value'     => $contacts_lang_data['contacts_company_address'],
                    'company_phone_value'       => preg_replace('/[^0-9+]/', '', $company_phone),
                    'company_phone_value_text' => $company_phone,
                    'company_email_value'       => $company_email,
                ));

                $footer = $this->CI->parser->parse(
                    'system/portal/tpl_footer',
                    $footer_data,
                    true
                );
                break;
        }

        if ($this->debug && $this->show_debug) {
            $this->CI->output->enable_profiler(true);
        }
        if (!$this->debug) {
            header('Content-Type: text/html; charset=UTF-8');
        }

        if (!in_array($this->template, $this->template_clear_list)) {
            include_once($this->templates_path . "main/top.php");
        }
        include_once($this->templates_path . $this->template . "/top.php");

        if ($_parse_template) {
            $this->CI->load->library('parser');

            $this->CI->html .= $this->CI->parser->parse($_parse_template, $_data, true);
        }

        echo $this->CI->html;

        include_once($this->templates_path . $this->template . "/bottom.php");
        if (!in_array($this->template, $this->template_clear_list)) {
            include_once($this->templates_path . "main/bottom.php");
        }

        if (!$output) {
            $tmp_out = ob_get_contents();
            ob_clean();

            return $tmp_out;
        }
    }

    private function _render_sidebar($group_list)
    {
        $group_menu = array();
        foreach ($group_list as $menu_group_list_key => $menu_group_list_item) {
            if (isset($menu_group_list_item['group_list_menu'])) {
                // Group title
                $group_menu[$menu_group_list_key]['group_list_title'] = isset($menu_group_list_item['group_list_title']) ? $menu_group_list_item['group_list_title'] : '';

                // Group menu
                $group_menu[$menu_group_list_key]['group_list_menu'] = $this->_build_group_menu(
                    $menu_group_list_item['group_list_menu'],
                    $menu_group_list_key
                );
            }
        }

        foreach ($group_menu as $group_menu_key => $group_menu_item) {
            $group_menu[$group_menu_key]['group_list_menu'] = $this->_render_group_menu(
                $group_menu_item['group_list_menu']
            );
        }

        return $this->CI->parser->parse(
            'system/sidebar/index',
            array(
                'group_list' => $group_menu,
            ),
            true
        );
    }

    private function _build_group_menu($group_list_menu, $group_key = null, $_key = null)
    {
        $_key = !is_null($_key) ? "{$_key}_" : '';

        foreach ($group_list_menu as $group_list_menu_key => $group_list_menu_item) {
            $key = "group_{$group_key}_submenu_{$_key}{$group_list_menu_key}";

            $menu_item = array(
                'key'     => $key,
                'content' => isset($group_list_menu_item['content']) ? $group_list_menu_item['content'] : null,
                'badge'   => isset($group_list_menu_item['badge']) ? $group_list_menu_item['badge'] : null,
            );

            if (isset($group_list_menu_item['submenu'])) {
                $menu_item['url']     = "#$menu_item[key]";
                $menu_item['submenu'] = $this->_build_group_menu(
                    $group_list_menu_item['submenu'],
                    $group_key,
                    "{$_key}{$group_list_menu_key}"
                );
                $menu_item['class']   = array('nav-link-submenu');
                $menu_item['attr']    = array('data-toggle="collapse"', 'role="button"');
            } else {
                $menu_item['url']     = isset($group_list_menu_item['url']) ? $group_list_menu_item['url'] : null;
                $menu_item['submenu'] = null;
                $menu_item['class']   = null;
                $menu_item['attr']    = null;
            }

            $out[] = $menu_item;
        }

        return $out;
    }

    private function _render_group_menu($build_menu)
    {
        $out = '';
        foreach ($build_menu as $build_menu_key => $build_menu_item) {
            $menu_item_data = array(
                'key'     => $build_menu_item['key'],
                'content' => $build_menu_item['content'],
                'url'     => $build_menu_item['url'],
                'class'   => !is_null($build_menu_item['class']) ? implode(' ', $build_menu_item['class']) : '',
                'attr'    => !is_null($build_menu_item['attr']) ? implode(' ', $build_menu_item['attr']) : '',
            );

            if (is_array($build_menu_item['badge'])) {
                $badge = $build_menu_item['badge'];
            } else {
                $badge = array('content' => $build_menu_item['badge']);
            }
            $menu_item_data['badge'] = $this->CI->h_lib->badge($badge);

            if (isset($build_menu_item['submenu'])) {
                $submenu_data['key']  = $menu_item_data['key'];
                $submenu_data['menu'] = $this->_render_group_menu($build_menu_item['submenu']);

                $menu_item_data['submenu'] = $this->CI->parser->parse('system/sidebar/submenu', $submenu_data, true);
            } else {
                $menu_item_data['submenu'] = '';
            }

            $out .= $this->CI->parser->parse('system/sidebar/menu_item', $menu_item_data, true);
        }

        return $out;
    }

}
