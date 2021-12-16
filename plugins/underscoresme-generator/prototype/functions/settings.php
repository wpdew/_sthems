<?php
//НАСТРОЙКИ ПАНЕЛИ АДМИНИСТРИРОВАНИЯ
//РЕГИСТРАЦИЯ ФУНКЦИИ НАСТРОЕК
function theme_settings_init(){
    register_setting( 'theme_settings', 'theme_settings' );
}

function my_admin_help() {
    $screen = get_current_screen();
    if ( $screen->base !== 'toplevel_page_settings' )
        return;
    $arg = [
		'id'       => 'my-plugin-default-help',
		'title'    => 'ДЛЯ _S темы',
		'content'  => '<p>В настройках темы Вы можете детально настроить некоторые параметры Вашего сайта</p>
        1. Социалки и телефоны - вкладка для добавления социальных сетей, почты и телефонов, которые отображаются в шапке и подвале сайта <br />
        2. Скрипты - вкладка для добавления скриптов аналитики и счетиков скриптами<br/ >
        3. Слайдер на главной - вкладка для добавления слайдера на главной странице сайта<br/ >
        4. Ссылки в подвале - вкладка для добавления меню в подвале сайта.
        <p>По редактированию и добавлению материалов на сайте читайте документацию <a href="/wp-content/themes/documents" target="_blank">здесь.</a></p>
        '
	];
    $screen->add_help_tab($arg);
}
add_action( 'admin_head', 'my_admin_help' );

// ДОБАВЛЕНИЕ НАСТРОЕК В МЕНЮ СТРАНИЦЫ
function add_settings_page() {
    add_menu_page( __( 'Опции темы' ), __( 'Опции темы' ), 'manage_options', 'settings', 'theme_settings_page');
}

//ДОБАВЛЕНИЕ ДЕЙСТВИЙ
add_action( 'admin_init', 'theme_settings_init' );
add_action( 'admin_menu', 'add_settings_page' );
//СОХРАНЕНИЕ НАСТРОЕК
function theme_settings_page() { 
    global $select_options; if ( ! isset( $_REQUEST['settings-updated'] ) ) $_REQUEST['settings-updated'] = false;
?>

<style>
.css-tabs-wrapper {
	max-width: 100%;
    width: 100%;
    min-height: 520px;
	margin: 50px auto 20px;
}
.css-tabs-wrapper > input {
	display: none;
}
.css-tabs-wrapper > label {
	display: block;
	float: left;
	padding: 10px 20px;
	margin-right: 5px;
	cursor: pointer;
	transition: all .25s ease-in-out;
}
.css-tabs-wrapper > label:hover,
.css-tabs-wrapper > input:checked + label {
	background: #4a9998;
	color: #fff;
}
.tabs {
	clear: both;
	min-height: 350px;
	perspective: 600px;
}
.tabs > div {
	position: absolute;
	opacity: 0;
	max-width: 100%;
    width: 93%;
	border: 2px solid #4a9998;
	padding: 10px 30px 40px;
	transform: rotateX(-25deg);
	transform-origin: top center;
	transition: opacity .3s ease-in-out, transform 1s;
}
#tab-nav-1:checked ~ .tabs > div:nth-of-type(1),
#tab-nav-2:checked ~ .tabs > div:nth-of-type(2),
#tab-nav-3:checked ~ .tabs > div:nth-of-type(3),
#tab-nav-4:checked ~ .tabs > div:nth-of-type(4){
	opacity: 1;
	z-index: 1;
	transform: rotateX(0deg);
}
@media only screen and (max-width : 992px) {
	.tabs {
		min-height: 420px;
	}
}
@media only screen and (max-width : 768px) {
	.tabs {
		min-height: auto;
	}
	.css-tabs-wrapper > label {
		display: none;
	}
	.tabs > div {
		border: none;
		padding-bottom: 5px;
		opacity: 1;
		position: static;
		transform: none;
	}
}
</style>

<div>
<h2 id="title">Настройка темы</h2>
<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
<div id="message" class="updated">
<p><strong>Настройки сохранены</strong></p>
</div>
<?php endif; ?>
<form method="post" action="options.php">
<?php settings_fields( 'theme_settings' ); ?>
<?php $options = get_option( 'theme_settings' ); ?>

<div class="css-tabs-wrapper">
	<!-- Первая вкладка -->
	<input type="radio" name="tabs" id="tab-nav-1" checked>
	<label for="tab-nav-1">Социалки и телефоны</label>
	<!-- Вторая вкладка -->
	<input type="radio" name="tabs" id="tab-nav-2">
	<label for="tab-nav-2">Скрипты</label>

	<div class="tabs">
		<div>
<table style="width: 99%;" class="wp-list-table widefat">

<tr valign="top">
<td><strong class="boldest">Телефон</strong></td>
<td>
<input placeholder="url" id="theme_settings[phone_url]" type="text" size="55" name="theme_settings[phone_url]" value="<?php esc_attr_e( $options['phone_url'] ); ?>" />
<input placeholder="текст ссылки" id="theme_settings[phone_sett]" type="text" size="55" name="theme_settings[phone_sett]" value="<?php esc_attr_e( $options['phone_sett'] ); ?>" />
</td>
<td><label> - Укажите Ваш № телефона. </label></td>
</tr>

<tr valign="top">
<td><strong class="boldest">Email</strong></td>
<td><input placeholder="Email" id="theme_settings[email_url]" type="text" size="55" name="theme_settings[email_url]" value="<?php esc_attr_e( $options['email_url'] ); ?>" /></td>
<td><label> - Укажите Ваш Email. </label></td>
</tr>

<tr valign="top">
<td><strong class="boldest">URL Facebook</strong></td>
<td><input id="theme_settings[facebook_url]" type="text" size="55" name="theme_settings[facebook_url]" value="<?php esc_attr_e( $options['facebook_url'] ); ?>" /></td>
<td><label> - Укажите путь к Вашему Facebook. </label></td>
</tr>

<tr valign="top">
<td><strong class="boldest">URL Instagram</strong></td>
<td><input id="theme_settings[instagram_url]" type="text" size="55" name="theme_settings[instagram_url]" value="<?php esc_attr_e( $options['instagram_url'] ); ?>" /></td>
<td><label> - Укажите путь к Вашему Instagram. </label></td>
</tr>

<tr valign="top">
<td><strong class="boldest">URL Twitter</strong></td>
<td><input id="theme_settings[twitt_url]" type="text" size="55" name="theme_settings[twitt_url]" value="<?php esc_attr_e( $options['twitt_url'] ); ?>" /></td>
<td><label> - Укажите путь к Вашему Twitter. </label></td>
</tr>

<tr valign="top">
<td><strong class="boldest">URL Telegram</strong></td>
<td><input id="theme_settings[telegram_url]" type="text" size="55" name="theme_settings[telegram_url]" value="<?php esc_attr_e( $options['telegram_url'] ); ?>" /></td>
<td><label> - Укажите путь к Вашему Telegram. </label></td>
</tr>

<tr valign="top">
<td><strong class="boldest">URL youtube</strong></td>
<td><input id="theme_settings[youtube_url]" type="text" size="55" name="theme_settings[youtube_url]" value="<?php esc_attr_e( $options['youtube_url'] ); ?>" /></td>
<td><label> - Укажите новый путь к Вашему youtube каналу. </label></td>
</tr>
</table>           

		</div>

		<div>

<table style="width: 99%;" class="wp-list-table widefat">
<tr valign="top">
<td>Текст и скрипты в секции header</td>
<td><textarea id="theme_settings[header]" name="theme_settings[header]" rows="5" cols="60"><?php esc_attr_e( $options['header'] ); ?></textarea></td>
<td><label> - Добавление своего script в header страницы перед &lt;/head&gt; </label></td>
</tr>


<tr valign="top">
<td>Текст и скрипты в секции body</td>
<td><textarea id="theme_settings[body]" name="theme_settings[body]" rows="5" cols="60"><?php esc_attr_e( $options['body'] ); ?></textarea></td>
<td><label> - Добавление своего script в body страницы после  &lt;body&gt;</label></td>
</tr>


<tr valign="top">
<td>Скрипты в подвале</td>
<td><textarea id="theme_settings[footer]" name="theme_settings[footer]" rows="5" cols="60"><?php esc_attr_e( $options['footer'] ); ?></textarea></td>
<td><label> - Добавление своего script в body страницы после  &lt;/body&gt;</label></td>
</tr>
</table>

        </div>
	</div>
</div>

<p><input name="submit" id="submit" class="button button-primary" value="Сохранить" type="submit"></p>
</form>

<pre>
Пример для вызова в шаблоне
Вызов инициализируем:
&lt;?php $options = get_option( &#039;theme_settings&#039; ); ?&gt;
Вывод:
&lt;?php echo $options[&#039;facebook_url&#039;] ?&gt;
<pre>

<?php } ?>