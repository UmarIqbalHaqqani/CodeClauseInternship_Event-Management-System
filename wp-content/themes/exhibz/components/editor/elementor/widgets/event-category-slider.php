<?php
namespace Elementor;

if (!defined('ABSPATH')) exit;

class Event_Category_Slider_Widget extends Widget_Base
{

   public $base;

   public function get_name(){
      return 'exhibz-event-category-slider';
   }

   public function get_title()
   {
      return esc_html__('Event Category Slider', 'exhibz');
   }

   public function get_icon()
   {
      return 'eicon-slider-push';
   }

   public function get_categories()
   {
      return ['exhibz-elements'];
   }

   protected function register_controls()
   {

      $this->start_controls_section(
         'section_tab',
         [
            'label' => esc_html__('Event Category', 'exhibz'),
         ]
      );

      $this->add_control(
        'category_style',
        [
            'label' => esc_html__('Category Style', 'exhibz'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'default' => 'style1',
            'options' => [
                'style1'  => esc_html__( 'Style One', 'exhibz' ),
            ],
        ]
    );

      $this->add_control(
        'categories_id',
        [
          'label'     => esc_html__( 'Select Category', 'exhibz' ),
          'type'      => Controls_Manager::SELECT2,
          'options'   => $this->event_category(),
          'multiple' =>true,
        ]
      );

      $this->add_control(
         'category_limit',
         [
               'label'   => esc_html__( 'Limit categories', 'exhibz' ),
               'type'    => Controls_Manager::NUMBER,
               'default' => 6,
               'min'     => 1,
               'step'    => 1,
         ]
      );

        $this->add_control(
         'post_sort_by',
         [
            'label' => esc_html__( 'Sort By', 'exhibz' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'DESC',
            'options' => [
               'ASC'  => esc_html__( 'ASC', 'exhibz' ),
               'DESC'  => esc_html__( 'DESC', 'exhibz' ),
            ],  
         ]
      );

      $this->add_control(
			'hide_empty',
			[
				'label'     => esc_html__( 'hide Empty?', 'exhibz' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
			]
		);

      $this->end_controls_section();

      $this->start_controls_section(
         'slider_settings_tab',
         [
            'label' => esc_html__('Slider Settings', 'exhibz'),
         ]
      );

      $this->add_control(
         'slider_items',
         [
             'label'         => esc_html__('Slide Items', 'exhibz'),
             'type'          => Controls_Manager::NUMBER,
             'default'       => 5
         ]
      );

      $this->add_control(
         'autoplay_slide',
         [
            'label' => esc_html__( 'Autoplay', 'exhibz' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__( 'Yes', 'exhibz' ),
            'label_off' => esc_html__( 'No', 'exhibz' ),
            'return_value' => 'yes',
            'default' => 'no'
         ]
      );

      $this->add_control(
         'show_navigation',
         [
            'label'       => esc_html__('Show Navigation', 'exhibz'),
            'type'        => Controls_Manager::SWITCHER,
            'label_on'    => esc_html__('Yes', 'exhibz'),
            'label_off'   => esc_html__('No', 'exhibz'),
            'default'     => 'yes'
         ]
      );

      $this->add_control(
			'left_arrow_icon',
			[
				'label' => esc_html__( 'Left Arrow Icon', 'exhibz' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'icon icon-left-arrow',
					'library' => 'solid',
				],
            'condition' => ['show_navigation' => 'yes']
			]
		);

      $this->add_control(
			'right_arrow_icon',
			[
				'label' => esc_html__( 'Right Arrow Icon', 'exhibz' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'icon icon-arrow-right',
					'library' => 'solid',
				],
            'condition' => ['show_navigation' => 'yes']
			]
		);

      $this->add_control(
         'slider_space_between',
         [
            'label'         => esc_html__('Slider Item Space', 'exhibz'),
            'description'   => esc_html__('Space between slides', 'exhibz'),
            'type'          => Controls_Manager::NUMBER,
            'return_value'  => 'yes',
            'default'       => 30
         ]
      );

      $this->end_controls_section();

      //style
      $this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style', 'exhibz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		); 

      $this->add_control(
         'category_title',
         [
             'label' => esc_html__('Title color', 'exhibz'),
             'type' => Controls_Manager::COLOR,
             'default' => '',
             'selectors' => [
                 '{{WRAPPER}} .ts-event-category-slider .event-slider-item .cat-content .ts-title a' => 'color: {{VALUE}};',
             
             ],
         ]
      );

      $this->add_control(
         'category_title_hover',
         [
             'label' => esc_html__('Title Hover color', 'exhibz'),
             'type' => Controls_Manager::COLOR,
             'default' => '',
             'selectors' => [
                 '{{WRAPPER}} .ts-event-category-slider .event-slider-item:hover .cat-content .ts-title a' => 'color: {{VALUE}};',
             
             ],
         ]
      );


      $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'category_title_typography',
				'label' => esc_html__( 'Typography', 'exhibz' ),
				'selector' => '{{WRAPPER}} .ts-event-category-slider .event-slider-item .cat-content .ts-title a',
			]
		);

      $this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Title Padding', 'exhibz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .ts-event-category-slider .event-slider-item .cat-content .ts-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

      $this->add_responsive_control(
			'category_bg_height',
			[
				'label'       => esc_html__('Category Shape Background Height', 'exhibz'),
				'description' => esc_html__('(-) Negative values are allowed', 'exhibz'),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => ['px', '%'],
				'range'       => [
					'%'  => [
						'min' => -100,
						'max' => 1000,
					],
					'px' => [
						'min' => -100,
						'max' => 1000,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .ts-event-category-slider .swiper-slide .event-slider-item .cat-bg a::after' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

      $this->add_responsive_control(
			'category_bg',
			[
				'label'       => esc_html__('Category Shape Position (x-axis)', 'exhibz'),
				'description' => esc_html__('(-) Negative values are allowed', 'exhibz'),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => ['px', '%'],
				'range'       => [
					'%'  => [
						'min' => -100,
						'max' => 200,
					],
					'px' => [
						'min' => -100,
						'max' => 200,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .ts-event-category-slider .swiper-slide .event-slider-item .cat-bg a::after' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

      $this->add_responsive_control(
			'category_bg_top',
			[
				'label'       => esc_html__('Category Shape Position (y-axis)', 'exhibz'),
				'description' => esc_html__('(-) Negative values are allowed', 'exhibz'),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => ['px', '%'],
				'range'       => [
					'%'  => [
						'min' => -100,
						'max' => 200,
					],
					'px' => [
						'min' => -100,
						'max' => 200,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .ts-event-category-slider .swiper-slide .event-slider-item .cat-bg a::after' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);


      $this->end_controls_section();

      $this->start_controls_section(
			'slider_style_section',
			[
				'label' => esc_html__( 'Slider Style', 'exhibz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		); 

      $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'slider_nav_icon_typography',
				'label' => esc_html__( 'Nav Icon Typography', 'exhibz' ),
				'selector' => '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev',
			]
		);

      $this->start_controls_tabs(
         'navigation_style_tabs'
      );

      $this->start_controls_tab(
         'navigation_style_normal_tab',
         [
           'label' => __( 'Normal', 'exhibz' ),
         ]
      );

      $this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'exhibz' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'color: {{VALUE}}',
				],
			]
		);

      $this->add_control(
			'icon_bg_color',
			[
				'label' => esc_html__( 'Icon Background Color', 'exhibz' ),
            'default' => '#00c1c1',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'background-color: {{VALUE}}',
				],
			]
		);

      $this->add_group_control(
         Group_Control_Border::get_type(),
         [
             'name' => 'slider_nav_border',
             'label' => __( 'Border', 'exhibz' ),
             'selector' => '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev',
         ]
      );

     $this->end_controls_tab();

     $this->start_controls_tab(
      'navigation_style_hover_tab',
      [
        'label' => __( 'Hover', 'exhibz' ),
      ]
      );

      $this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Icon Hover Color', 'exhibz' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-next:hover, {{WRAPPER}} .swiper-button-prev:hover' => 'color: {{VALUE}}',
				],
			]
		);

      $this->add_control(
			'icon_bg_color_hover',
			[
				'label' => esc_html__( 'Icon Hover Background Color', 'exhibz' ),
				'type' => Controls_Manager::COLOR,
                'default' => '#00c1c1',
				'selectors' => [
					'{{WRAPPER}} .swiper-button-next:hover, {{WRAPPER}} .swiper-button-prev:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

      $this->end_controls_tab();


      $this->end_controls_tabs();

      $this->end_controls_section();


   }

   protected function render() {

        $settings              = $this->get_settings();
        $category_style        = $settings['category_style'];
        $categories_id         = $settings['categories_id'];
        $settings['widget_id'] = $this->get_id();
        $autoplay_slide        = $settings['autoplay_slide'];
        $show_navigation       = $settings['show_navigation'];
        $slider_space_between  = $settings['slider_space_between'];
        $slider_items          = $settings['slider_items'];

         $exhibz_event_category_settings = array(
            'category_style' => $settings['category_style'],
            'hide_empty'     => $settings['hide_empty'],
            'category-options'  => array(
               'category_limit' => $settings['category_limit'],
               'categories_id'  => $settings['categories_id'],
               'post_sort_by'   => $settings['post_sort_by']
            )
		   );

         $slide_controls    = [
            'autoplay_slide'       => $autoplay_slide, 
            'show_navigation'      => $show_navigation, 
            'slider_space_between' => $slider_space_between,
            'slider_items'         => $slider_items,
            'widget_id'            => $this->get_id()
        ];

         $slide_controls = \json_encode($slide_controls);
        include (locate_template("components/editor/elementor/widgets/style/event-category-slider/event-category-slider.php", false, false ));  

   }
   protected function content_template(){}

   public function event_category(){
    if(!class_exists('\Etn\Bootstrap')){
      return [];
    }
    $tax_terms = get_terms('etn_category', array('hide_empty' => false));
    $category_list = [];
     
    foreach($tax_terms as $term_single) {      
      $category_list[$term_single->term_id] = [$term_single->name];
    }
  
    return $category_list;
  }
}
