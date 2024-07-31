<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit;


class Exhibz_Fancy_Title_Widget extends Widget_Base {


  public $base;

    public function get_name() {
        return 'exhibz-fancy-title';
    }

    public function get_title() {

        return esc_html__( 'Exhibz Fancy Title', 'exhibz' );

    }

    public function get_icon() { 
        return 'eicon-post-title';
    }

    public function get_categories() {
        return [ 'exhibz-elements' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__('Title settings', 'exhibz'),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'exhibz_first_title', [
                'label' => esc_html__('Title', 'exhibz'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('* See Your For 2024', 'exhibz'),
                'label_block' => true,
            ]
        );
        $this->add_control(
			'exhibz_first_title_items',
			[
				'label' => esc_html__('Exhibz Title One', 'exhibz'),
				'type' => \Elementor\Controls_Manager::REPEATER,
                'prevent_empty' => false,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'exhibz_first_title' =>  esc_html__('* See Your For 2024', 'exhibz'),
					],
					[
						'exhibz_first_title' =>  esc_html__('* See Your For 2024', 'exhibz'),
					],	
				],
				'title_field' => '{{{ exhibz_first_title }}}',
			]
        );

        $repeate = new \Elementor\Repeater();

        $repeate->add_control(
            'exhibz_sec_title', [
                'label' => esc_html__('Title', 'exhibz'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Reach YOUR DREAM *', 'exhibz'),
                'label_block' => true,
            ]
        );
        $this->add_control(
			'exhibz_sec_title_items',
			[
				'label' => esc_html__('Exhibz Title Two', 'exhibz'),
				'type' => \Elementor\Controls_Manager::REPEATER,
                'prevent_empty' => false,
				'fields' => $repeate->get_controls(),
				'default' => [
					[
						'exhibz_sec_title' =>  esc_html__('Reach YOUR DREAM *', 'exhibz'),
					],
					[
						'exhibz_sec_title' =>  esc_html__('Reach YOUR DREAM *', 'exhibz'),
					],	
				],
				'title_field' => '{{{ exhibz_sec_title }}}',
			]
        );
         
        $this->end_controls_section();

        //Title Style Section
		$this->start_controls_section(
			'section_title_style', [
				'label'     => esc_html__('Title Style', 'exhibz'),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_group_control(Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .exhibz-fancy-wrapper .exhibz-fancy-title .exhibz-scroll-text',
			]
		);
        $this->add_control(
			'title_color', [
				'label'     => esc_html__('Title color', 'exhibz'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .exhibz-fancy-wrapper .exhibz-fancy-title .exhibz-scroll-text' => 'color: {{VALUE}};',
				],
			]
		);
        $this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Title margin', 'exhibz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .exhibz-fancy-wrapper .exhibz-fancy-title .exhibz-scroll-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->add_control(
            'title_opacity',
            [
                'label' => esc_html__( 'Opacity', 'exhibz' ),
                'description' => esc_html__( 'Opacity will be (0-1), default value 1', 'exhibz' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '1',
                'min' => 0,
                'max' => 1,
                'step' => .1,
                'render_type' => 'none',
                'frontend_available' => true,
                'selectors' => [
                    "{{WRAPPER}} .exhibz-fancy-wrapper .exhibz-fancy-title .exhibz-scroll-text" => 'opacity:{{UNIT}}'
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render( ) { 
        $settings = $this->get_settings();

    ?>  
        <div class="exhibz-fancy-wrapper">
            <div class="exhibz-fancy-title exh-fancy-first">
                <?php if( $settings['exhibz_first_title_items'] ): ?>
                <?php foreach( $settings['exhibz_first_title_items'] as $titleone ): ?>
                    <h1 class="exhibz-scroll-text text-first">
                        <?php echo esc_html($titleone['exhibz_first_title']) ?>
                    </h1> 
                <?php endforeach; endif; ?>
            </div>
        
            <div class="exhibz-fancy-title exh-fancy-sec ">
                <?php if( $settings['exhibz_sec_title_items'] ): ?>
                <?php foreach( $settings['exhibz_sec_title_items'] as $titletwo ): ?>
                    <h1 class="exhibz-scroll-text text-second"> 
                        <?php echo esc_html($titletwo['exhibz_sec_title']) ?>
                    </h1>
                <?php endforeach; endif; ?>
            </div>
        </div>
    <?php  
    }
    protected function content_template() { }
}