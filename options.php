<?php
class HSSB_SettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Hueman Scrollable Sharrre Bar', 
            'manage_options', 
            'hueman-scrollable-sharrre-bar', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'HSSB_options' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Hueman Scrollable Sharrre Bar Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'HSSB_option_group' );   
                do_settings_sections( 'hueman-scrollable-sharrre-bar' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'HSSB_option_group', // Option group
            'HSSB_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'HSSB Custom Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'hueman-scrollable-sharrre-bar' // Page
        );  

        add_settings_field(
            'top_spacing', // ID
            'Spacing from the top of the page to the top of the Sharrre bar', // Title 
            array( $this, 'top_spacing_callback' ), // Callback
            'hueman-scrollable-sharrre-bar', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'min_width', 
            'Scroll turns off at this width', 
            array( $this, 'min_width_callback' ), 
            'hueman-scrollable-sharrre-bar', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['top_spacing'] ) )
            $new_input['top_spacing'] = absint( $input['top_spacing'] );

        if( isset( $input['min_width'] ) )
            $new_input['min_width'] = sanitize_text_field( $input['min_width'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function top_spacing_callback()
    {
        printf(
            '<input type="text" id="top_spacing" name="HSSB_options[top_spacing]" value="%s" />',
            isset( $this->options['top_spacing'] ) ? esc_attr( $this->options['top_spacing']) : '50'
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function min_width_callback()
    {
        printf(
            '<input type="text" id="min_width" name="HSSB_options[min_width]" value="%s" />',
            isset( $this->options['min_width'] ) ? esc_attr( $this->options['min_width']) : '719'
        );
    }
}