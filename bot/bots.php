<?php
/*
Plugin Name: Bot Counter
Plugin URI: http://rightbrain.com
Description: Plugin is counting bots visits
Author: Greg
Version: 1.0
Author URI: http://prince@msdn.net.bd
*/
function bot_install(){
    global $wpdb;
    $table=$wpdb->prefix."bot_counter";
    $structure= "CREATE TABLE $table(
    id INT(9) NOT NULL AUTO_INCREMENT,
    bot_name VARCHAR(80) NOT NULL,
    bot_mark VARCHAR(20) NOT NULL,
    bot_visits INT (9) DEFAULT 0,
    UNIQUE KEY id (id)

    );";
 $wpdb->query($structure);
    $wpdb->query("INSERT INTO $table(bot_name,bot_mark) VALUES ('Google Bot','googlebot')");
    $wpdb->query("INSERT INTO $table(bot_name,bot_mark)VALUES ('Yahoo Slurp','yahoo')");



}
add_action('activate_bot/bots.php', 'bot_install');

function bot()
{
    global $wpdb;
    $browser_name = $_SERVER['HTTP_USER_AGENT'];
    $bots = $wpdb->get_results("SELECT * FROM ".
    $wpdb->prefix."bot_counter");

    foreach($bots as $bot)
    {
        if(eregi($bot->bot_mark, $browser_name))
        {
            $wpdb->query("UPDATE ".$wp->prefix."bot_counter
                SET bot_visits = bot_visits+1 WHERE id = ".$bot->id);

            break;
        }
    }
}
add_action('wp_footer', 'bot');
function bot_menu()
{
    global $wpdb;
    include 'bot-admin.php';
}

function bot_admin_actions()
{
    add_options_page("Bot Counter", "Bot Counter", "manage_options", "bot-counter", "bot_menu");
}

add_action('admin_menu', 'bot_admin_actions');