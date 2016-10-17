<?php
function get_calendar_html($calendarDateStart, $calendarDateEnd, $append) {
	
		$output = NULL;
		
		$start = new DateTime($calendarDateStart);
		$start->modify('first day of this month');
		
		$end = new DateTime($calendarDateEnd);
		$end->modify('first day of next month');
		
		$interval = DateInterval::createFromDateString('1 month');
		
		$period = new DatePeriod($start, $interval, $end);
		
		$current_month_number = 1;
		
		$total_months = 0;
		
		$months = array();
		
		foreach ($period as $month) {
		
			$months[] = $month;
		
			$total_months++;
		}
		
		$output .= "<div class=\"cal_container\">" . PHP_EOL;
		
		foreach ($months as $current_month) {
		
			$current_month_days = $current_month->format('t');
			$current_month_start = $current_month->format('N');
			$current_month_finish = date('N', strtotime($current_month->format("Y-m-{$current_month_days}")));
			$current_month_weeks = $current_month_start > 5 ? 6 : 5;
			$current_month_counter = 1;
		
			$previous_month_days = date('t', strtotime($current_month->format('Y-m-d') . ' -1 month'));
			$previous_month_start = $current_month_start - 1;
		
			$next_month_counter = 1;
		
			$output .= "<div class=\"cal_month_container\" id=\"cal_month_{$current_month_number}\">" . PHP_EOL;
		
			$output .= '<h3>' . strftime('%B %Y', strtotime($current_month->format('Y-m-d'))) . '</h3>';
		
			$output .= '<div class="nav">';
		
			if ($current_month_number > 1) {
					
				$output .= "<a class=\"prev\" href=\"JavaScript:$.cal_month_show('" . ($current_month_number - 1) . "')\">&lt;</a>";
			}
		
			if ($current_month_number < $total_months) {
					
				$output .= "<a class=\"next\" href=\"JavaScript:$.cal_month_show('" . ($current_month_number + 1) . "')\">&gt;</a>";
			}
		
			$output .= '</div>';
		
			$output .= '<table class="cal_month">' . PHP_EOL;
		
			$output .= '<tr class="cal_week">' . PHP_EOL;
		
			$output .= '<th class="cal_day_header">' . strftime('%a', strtotime('next Monday')) . '</th>';
			$output .= '<th class="cal_day_header">' . strftime('%a', strtotime('next Tuesday')) . '</th>';
			$output .= '<th class="cal_day_header">' . strftime('%a', strtotime('next Wednesday')) . '</th>';
			$output .= '<th class="cal_day_header">' . strftime('%a', strtotime('next Thursday')) . '</th>';
			$output .= '<th class="cal_day_header">' . strftime('%a', strtotime('next Friday')) . '</th>';
			$output .= '<th class="cal_day_header">' . strftime('%a', strtotime('next Saturday')) . '</th>';
			$output .= '<th class="cal_day_header">' . strftime('%a', strtotime('next Sunday')) . '</th>';
		
			$output .= '</tr>' . PHP_EOL;
		
			for($week = 1; $week <= $current_month_weeks; $week ++) {
		
				$output .= '<tr class="cal_week">' . PHP_EOL;
		
				for($weekday = 1; $weekday <= 7; $weekday ++) {
		
					$class = '';
		
					if (($current_month_counter - $current_month_start) < 0) {
		
						$day = $previous_month_days - $previous_month_start + $current_month_counter;
							
						$date = date("Y-m-" . sprintf("%02d", $day), strtotime($current_month->format('Y-m-d') . ' -1 month'));
		
						$class = 'previous';
							
					} else if (($current_month_counter - $current_month_start) >= $current_month_days) {
		
						$day = $next_month_counter;
							
						$date = date("Y-m-" . sprintf("%02d", $day), strtotime($current_month->format('Y-m-d') . ' +1 month'));
		
						$class = 'next';
		
						$next_month_counter ++;
							
					} else {
		
						$day = $current_month_counter - $current_month_start + 1;
							
						$date = $current_month->format("Y-m-" .  sprintf("%02d", $day));
		
						$class = 'current';
		
						if ($date == date('Y-m-d')) {
		
							$class .= ' today';
						}
					}
		
					if (in_array($weekday, array(6,7))) {
		
						$class .= ' weekend';
					}
		
					if (
							strtotime($date) < strtotime($calendarDateStart)
							||
							strtotime($date) > strtotime($calendarDateEnd)
					) {
							
						$class .= ' out_of_range';
					}
		
					$output .= "<td class=\"cal_day {$class}\">";
					$output .= "<span class=\"number\">{$day}</span>";
					if (!empty($append['day'][$date])) {
							
						$output .= $append['day'][$date];
					}
					$output .= '</td>' . PHP_EOL;
		
					$current_month_counter ++;
				}
		
				$output .= '</tr>' . PHP_EOL;
			}
		
			$output .= '</table>' . PHP_EOL;
		
			$output .= '</div>' . PHP_EOL;
		
			$current_month_number++;
		}
		
		$output .= "</div>" . PHP_EOL;
		
		return $output;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>cal</title>
<style type="text/css">
/* RESET START */
/* http://meyerweb.com/eric/tools/css/reset/ 
   v2.0 | 20110126
   License: none (public domain)
*/
html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video
	{
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}

article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section
	{
	display: block;
}

html, body {
	line-height: 1;
	font-family: Arial,Helvetica,sans-serif;
	height: 100%;
}
#wrapper {
	min-height: 100%;
	margin-bottom: -3em;
}
#wrapper:after {
	content: "";
  	display: block;
}
#wrapper:after, footer {
	height: 3em;
}
ol,ul {
	list-style: none;
}

blockquote,q {
	quotes: none;
}

blockquote:before,blockquote:after,q:before,q:after {
	content: '';
	content: none;
}

table {
	border-collapse: collapse;
	border-spacing: 0;
}
/* RESET END */

.calendar_big {
	float: left;
	display: inline;
	width: auto;
	
}

.calendar_small_container {
	float: left;
	display: inline;
	width: auto;
	clear: both;

}

.calendar_small {
	float: left;
	display: inline;
	width: auto;
}


.cal_container {

}

.cal_month_container {
	
	float: left;
	display: inline;
	width: auto;
	padding: .2em;
	margin: 0 0em 0 0;
}

.cal_month_container h3 {
	float: left;
	display: inline;
	width: auto;
	
	font-weight: bold;
	padding: .3em;
}

.cal_month_container .nav {
	float: right;
	display: inline;
	width: auto;
	padding: .5em;
}

.cal_month_container .nav a {
	float: left;
	display: inline;
	width: auto;
	
	margin: 0 0 0 .5em;
	
	text-decoration:none;
	color: #000;
	font-weight: bold;
}

.cal_month_container .nav .prev {
}

.cal_month_container .nav .next {
}

.cal_month {
	margin: 0;
	padding: 0;
	clear: left;
}

.cal_week {}

.cal_day_header {
	font-size: .7em;
	font-weight: bold;
	padding: .5em 1em;
	border: 1px solid #000;
}

.cal_day {
	padding: .4em .7em;
	margin: 0;
	border: 1px solid #000;
	text-align: right;
	
}

.calendar_big h3 {
	font-size: 1.6em;
}

.calendar_big .cal_day {
	padding: 0.7em 2.9em;
	margin: 0;
	border: 1px solid #000;
	text-align: right;
	
}

.cal_day span {
	font-size: .7em;
}

.calendar_big .cal_day span {
	font-size: 2.5em;
}

.cal_week .previous span {
	color: #ccc;
}

.cal_week .next span {
	color: #ccc;
}

.cal_week .current span {}

.cal_week .today span {
	
}

.cal_week .out_of_range span {
 	color: #fff !important;
}

.cal_week .weekend {
	background: #eee;
	font-weight: bold;
}
</style>
</head>
<body>
<?php
$m = '12';

$current = "2015-{$m}-01";
$previous = date('Y-m-d', strtotime("{$current} - 1 month"));
$next = date('Y-m-d', strtotime("{$current} + 1 month"));
$more = date('Y-m-d', strtotime("{$current} + 2 month"));

echo '<section class="calendar_big">';
echo get_calendar_html($current, date('Y-m-t', strtotime($current)));
echo '</section>';

echo '<section class="calendar_small_container">';

echo '<article class="calendar_small">';
echo get_calendar_html($previous, date('Y-m-t', strtotime($previous)));
echo '</article>';

echo '<article class="calendar_small">';
echo get_calendar_html($next, date('Y-m-t', strtotime($next)));
echo '</article>';

/*
echo '<article class="calendar_small">';
echo get_calendar_html($more, date('Y-m-t', strtotime($more)));
echo '</article>';
*/

echo '</section>';



?>

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	    
<script>
	
//<![CDATA[
	    
$(document).ready(function() {

	/*
	$('.cal_month_container').hide();
	$('#cal_month_1').show();

	$.cal_month_show = function(month) {

		$('.cal_month_container').hide();
		$('#cal_month_' + month).show();	
	};
	*/
});
		
//]]> 

</script>	    
</body>
</html>