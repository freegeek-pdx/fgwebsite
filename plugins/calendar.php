<?php
/*
Plugin Name: Calendar
Plugin URI: http://www.kieranoshea.com/
Description: This plugin allows you to display a calendar of all your events and appointments as a page on your site.
Author: Kieran O'Shea
Author URI: http://www.kieranoshea.com
Version: 1.1.2
*/

// Define the table for the plugin.
define('WP_CALENDAR_TABLE', $table_prefix . 'calendar');

// Puts the events management link under manage
function wp_events_admin_menu($content)
{
	global $submenu;
	$submenu['edit.php'][50] = array(__('Calendar'), 9, 'edit-calendar.php');
}
add_action('admin_menu', 'wp_events_admin_menu');


// Now we define the tonne of functions the calendar needs to run

 function next_link($cur_year,$cur_month)
 {
    $mod_rewrite_months = array(1=>'jan','feb','mar','apr','may','jun','jul','aug','sept','oct','nov','dec');

    $next_year = $cur_year + 1;

    if ($cur_month == 12)
    {
       return '<a href="' . get_bloginfo('wpurl') . '/calendar/' . $next_year . '/jan">Next &raquo;</a>';
    }
    else
    {
       $next_month = $cur_month + 1;
       $month = $mod_rewrite_months[$next_month];
       return '<a href="' . get_bloginfo('wpurl') . '/calendar/' . $cur_year . '/' . $month . '">Next &raquo;</a>';
    }
 }

 function prev_link($cur_year,$cur_month)
 {
    $mod_rewrite_months = array(1=>'jan','feb','mar','apr','may','jun','jul','aug','sept','oct','nov','dec');

    $last_year = $cur_year - 1;

    if ($cur_month == 1)
    {
       return '<a href="' . get_bloginfo('wpurl') . '/calendar/'. $last_year .'/dec">&laquo; Prev</a>';
    }
    else
    {
       $next_month = $cur_month - 1;
       $month = $mod_rewrite_months[$next_month];
       return '<a href="' . get_bloginfo('wpurl') . '/calendar/' . $cur_year . '/' . $month . '">&laquo; Prev</a>';
    }
 }

 function grab_events($y,$m,$d)
 {
     global $wpdb;
     $date = $y . '-' . $m . '-' . $d;
     $output = '';
     
     // Firstly we check for conventional events. These will form the first instance of a recurring event
     // or the only instance of a one-off event
     $events = $wpdb->get_results("SELECT * FROM " . WP_CALENDAR_TABLE . " WHERE event_begin <= '$date' AND event_end >= '$date' AND event_recur = 'S' ORDER BY event_id");
     if (!empty($events))
     {
         foreach($events as $event)
         {
	      if ($event->event_time == "00:00:00")
		{
			$event_time = '';
		}
	      else
		{
			$event_time = 'Time: ' . date("H:i", strtotime($event->event_time)) . '<br /><br />';
		}
	      //$output .= '<br />* <a style="color:#333333; border-bottom: 1px dotted #333333;" title="' . $event_time . $event->event_desc . '">' . $event->event_title . '</a>';
	      $output .= '<br />* <span class="calnk" nowrap="nowrap"><a>' . $event->event_title . '<span>' . $event_time . '' . $event->event_desc . '</span></a></span>';
         }
     }

	// Even if there were results for that query, we may still have events recurring 
	// from the past on this day. We now methodically check the for these events

	/* 
	 The yearly code - easy because the day and month will be the same, so we return all yearly
	 events that match the date part. Out of these we show those with a repeat of 0, and fast-foward
	 a number of years for those with a value more than 0. Those that land in the future are displayed.
	*/

	
	// Deal with forever recurring year events
	$events = $wpdb->get_results("SELECT * FROM " . WP_CALENDAR_TABLE . " WHERE event_recur = 'Y' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin) AND event_repeats = 0 ORDER BY event_id");

	if (!empty($events))
     	{
       	  foreach($events as $event)
          {
	    // This is going to get complex so lets setup what we would place in for 
	    // an event so we can drop it in with ease
	    if ($event->event_time == "00:00:00")
	    {
		$event_time = '';
	    }
	    else
	    {
		$event_time = 'Time: ' . date("H:i", strtotime($event->event_time)) . '<br /><br />';
	    }
	    $event_out = '<br />* <span class="calnk" nowrap="nowrap"><a>' . $event->event_title . '<span>' . $event_time . '' . $event->event_desc . '</span></a></span>';

	    // Technically we don't care about the years, but we need to find out if the 
	    // event spans the turn of a year so we can deal with it appropriately.
	    $year_begin = date('Y',strtotime($event->event_begin));
	    $year_end = date('Y',strtotime($event->event_end));

	    if ($year_begin == $year_end)
	    {
		if (date('m-d',strtotime($event->event_begin)) <= date('m-d',strtotime($date)) && 
			date('m-d',strtotime($event->event_end)) >= date('m-d',strtotime($date)))
		{
	      		$output .= $event_out;
		}
	    }
	    else if ($year_begin < $year_end)
	    {
		if (date('m-d',strtotime($event->event_begin)) <= date('m-d',strtotime($date)) || 
			date('m-d',strtotime($event->event_end)) >= date('m-d',strtotime($date)))
		{
	      		$output .= $event_out;
		}
	    }
          }
     	}
	
	// Now the ones that happen a finite number of times
	$events = $wpdb->get_results("SELECT * FROM " . WP_CALENDAR_TABLE . " WHERE event_recur = 'Y' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin) AND event_repeats != 0 AND (EXTRACT(YEAR FROM '$date')-EXTRACT(YEAR FROM event_begin)) <= event_repeats ORDER BY event_id");
	if (!empty($events))
     	{
       	  foreach($events as $event)
          {
	    // This is going to get complex so lets setup what we would place in for 
	    // an event so we can drop it in with ease
	    if ($event->event_time == "00:00:00")
	    {
		$event_time = '';
	    }
	    else
	    {
		$event_time = 'Time: ' . date("H:i", strtotime($event->event_time)) . '<br /><br />';
	    }
	    $event_out = '<br />* <span class="calnk" nowrap="nowrap"><a>' . $event->event_title . '<span>' . $event_time . '' . $event->event_desc . '</span></a></span>';

	    // Technically we don't care about the years, but we need to find out if the 
	    // event spans the turn of a year so we can deal with it appropriately.
	    $year_begin = date('Y',strtotime($event->event_begin));
	    $year_end = date('Y',strtotime($event->event_end));

	    if ($year_begin == $year_end)
	    {
		if (date('m-d',strtotime($event->event_begin)) <= date('m-d',strtotime($date)) && 
			date('m-d',strtotime($event->event_end)) >= date('m-d',strtotime($date)))
		{
	      		$output .= $event_out;
		}
	    }
	    else if ($year_begin < $year_end)
	    {
		if (date('m-d',strtotime($event->event_begin)) <= date('m-d',strtotime($date)) || 
			date('m-d',strtotime($event->event_end)) >= date('m-d',strtotime($date)))
		{
	      		$output .= $event_out;
		}
	    }
          }
     	}	

	/* 
	  The monthly code - just as easy because as long as the day of the month is correct, then we 
	  show the event
	*/

	// The monthly events that never stop recurring
	$events = $wpdb->get_results("SELECT * FROM " . WP_CALENDAR_TABLE . " WHERE event_recur = 'M' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin) AND event_repeats = 0 ORDER BY event_id");
	if (!empty($events))
     	{
       	  foreach($events as $event)
          {
	    // This is going to get complex so lets setup what we would place in for 
	    // an event so we can drop it in with ease
	    if ($event->event_time == "00:00:00")
	    {
		$event_time = '';
	    }
	    else
	    {
		$event_time = 'Time: ' . date("H:i", strtotime($event->event_time)) . '<br /><br />';
	    }
	    $event_out = '<br />* <span class="calnk" nowrap="nowrap"><a>' . $event->event_title . '<span>' . $event_time . '' . $event->event_desc . '</span></a></span>';

	    // Technically we don't care about the years or months, but we need to find out if the 
	    // event spans the turn of a year or month so we can deal with it appropriately.
	    $month_begin = date('m',strtotime($event->event_begin));
	    $month_end = date('m',strtotime($event->event_end));

	    if ($month_begin == $month_end)
	    {
		if (date('d',strtotime($event->event_begin)) <= date('d',strtotime($date)) && 
			date('d',strtotime($event->event_end)) >= date('d',strtotime($date)))
		{
	      		$output .= $event_out;
		}
	    }
	    else if ($month_begin < $month_end)
	    {
		if ( ($event->event_begin <= date('Y-m-d',strtotime($date))) && (date('d',strtotime($event->event_begin)) <= date('d',strtotime($date)) || 
			date('d',strtotime($event->event_end)) >= date('d',strtotime($date))) )
		{
	      		$output .= $event_out;
		}
	    }
          }
     	}


	// Now the ones that happen a finite number of times
	$events = $wpdb->get_results("SELECT * FROM " . WP_CALENDAR_TABLE . " WHERE event_recur = 'M' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin) AND event_repeats != 0 AND (PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM '$date'),EXTRACT(YEAR_MONTH FROM event_begin))) <= event_repeats ORDER BY event_id");
	if (!empty($events))
     	{
       	  foreach($events as $event)
          {
	    // This is going to get complex so lets setup what we would place in for 
	    // an event so we can drop it in with ease
	    if ($event->event_time == "00:00:00")
	    {
		$event_time = '';
	    }
	    else
	    {
		$event_time = 'Time: ' . date("H:i", strtotime($event->event_time)) . '<br /><br />';
	    }
	    $event_out = '<br />* <span class="calnk" nowrap="nowrap"><a>' . $event->event_title . '<span>' . $event_time . '' . $event->event_desc . '</span></a></span>';

	    // Technically we don't care about the years or months, but we need to find out if the 
	    // event spans the turn of a year or month so we can deal with it appropriately.
	    $month_begin = date('m',strtotime($event->event_begin));
	    $month_end = date('m',strtotime($event->event_end));

	    if ($month_begin == $month_end)
	    {
		if (date('d',strtotime($event->event_begin)) <= date('d',strtotime($date)) && 
			date('d',strtotime($event->event_end)) >= date('d',strtotime($date)))
		{
	      		$output .= $event_out;
		}
	    }
	    else if ($month_begin < $month_end)
	    {
		if ( ($event->event_begin <= date('Y-m-d',strtotime($date))) && (date('d',strtotime($event->event_begin)) <= date('d',strtotime($date)) || 
			date('d',strtotime($event->event_end)) >= date('d',strtotime($date))) )
		{
	      		$output .= $event_out;
		}
	    }
          }
     	}


	/* 
	  Weekly - well isn't this fun! We need to scan all weekly events, find what day they fell on
	  and see if that matches the current day. If it does, we check to see if the repeats are 0. 
	  If they are, display the event, if not, we fast forward from the original day in week blocks 
	  until the number is exhausted. If the date we arrive at is in the future, display the event.
	*/

	// The weekly events that never stop recurring
	$events = $wpdb->get_results("SELECT * FROM " . WP_CALENDAR_TABLE . " WHERE event_recur = 'W' AND '$date' >= event_begin AND event_repeats = 0 ORDER BY event_id");
	if (!empty($events))
     	{
       	  foreach($events as $event)
          {
	    // This is going to get complex so lets setup what we would place in for 
	    // an event so we can drop it in with ease
	    if ($event->event_time == "00:00:00")
	    {
		$event_time = '';
	    }
	    else
	    {
		$event_time = 'Time: ' . date("H:i", strtotime($event->event_time)) . '<br /><br />';
	    }
	    $event_out = '<br />* <span class="calnk" nowrap="nowrap"><a>' . $event->event_title . '<span>' . $event_time . '' . $event->event_desc . '</span></a></span>';

	    // Now we are going to check to see what day the original event
	    // fell on and see if the current date is both after it and on 
	    // the correct day. If it is, display the event!
	    $day_start_event = date('D',strtotime($event->event_begin));
	    $day_end_event = date('D',strtotime($event->event_end));
	    $current_day = date('D',strtotime($date));

	    $plan = array();
	    $plan['Mon'] = 1;
	    $plan['Tue'] = 2;
	    $plan['Wed'] = 3;
	    $plan['Thu'] = 4;
	    $plan['Fri'] = 5;
	    $plan['Sat'] = 6;
	    $plan['Sun'] = 7;

	    if ($plan[$day_start_event] > $plan[$day_end_event])
	    {
		if (($plan[$day_start_event] <= $plan[$current_day]) || ($plan[$current_day] <= $plan[$day_end_event]))
	    	{
			$output .= $event_out;
	    	}
	    }
	    else if (($plan[$day_start_event] < $plan[$day_end_event]) || ($plan[$day_start_event]== $plan[$day_end_event]))
	    {
		if (($plan[$day_start_event] <= $plan[$current_day]) && ($plan[$current_day] <= $plan[$day_end_event]))
	    	{
			$output .= $event_out;
	    	}		
	    }
	    
          }
     	}

	// The weekly events that have a limit on how many times they occur
	$events = $wpdb->get_results("SELECT * FROM " . WP_CALENDAR_TABLE . " WHERE event_recur = 'W' AND '$date' >= event_begin AND event_repeats != 0 AND (event_repeats*7) >= (TO_DAYS('$date') - TO_DAYS(event_end)) ORDER BY event_id");
	if (!empty($events))
     	{
       	  foreach($events as $event)
          {
	    // This is going to get complex so lets setup what we would place in for 
	    // an event so we can drop it in with ease
	    if ($event->event_time == "00:00:00")
	    {
		$event_time = '';
	    }
	    else
	    {
		$event_time = 'Time: ' . date("H:i", strtotime($event->event_time)) . '<br /><br />';
	    }
	    $event_out = '<br />* <span class="calnk" nowrap="nowrap"><a>' . $event->event_title . '<span>' . $event_time . '' . $event->event_desc . '</span></a></span>';

	    // Now we are going to check to see what day the original event
	    // fell on and see if the current date is both after it and on 
	    // the correct day. If it is, display the event!
	    $day_start_event = date('D',strtotime($event->event_begin));
	    $day_end_event = date('D',strtotime($event->event_end));
	    $current_day = date('D',strtotime($date));

	    $plan = array();
	    $plan['Mon'] = 1;
	    $plan['Tue'] = 2;
	    $plan['Wed'] = 3;
	    $plan['Thu'] = 4;
	    $plan['Fri'] = 5;
	    $plan['Sat'] = 6;
	    $plan['Sun'] = 7;

	    if ($plan[$day_start_event] > $plan[$day_end_event])
	    {
		if (($plan[$day_start_event] <= $plan[$current_day]) || ($plan[$current_day] <= $plan[$day_end_event]))
	    	{
			$output .= $event_out;
	    	}
	    }
	    else if (($plan[$day_start_event] < $plan[$day_end_event]) || ($plan[$day_start_event]== $plan[$day_end_event]))
	    {
		if (($plan[$day_start_event] <= $plan[$current_day]) && ($plan[$current_day] <= $plan[$day_end_event]))
	    	{
			$output .= $event_out;
	    	}		
	    }

          }
     	}
 
     return $output;
 }

 function calendar()
 {

    $name_days = array(1=>'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
    $name_months = array(1=>'January','February','March','April','May','June','July','August','September','October','November','December');

    if (empty($_GET['month']) || empty($_GET['year']))
    {
        $c_year = date("Y");
        $c_month = date("m");
        $c_day = date("d");
    }
    if ($_GET['year'] <= 3000 && $_GET['year'] >= 0)
    {
        if ($_GET['month'] == 'jan' || $_GET['month'] == 'feb' || $_GET['month'] == 'mar' || $_GET['month'] == 'apr' || $_GET['month'] == 'may' || $_GET['month'] == 'jun' || $_GET['month'] == 'jul' || $_GET['month'] == 'aug' || $_GET['month'] == 'sept' || $_GET['month'] == 'oct' || $_GET['month'] == 'nov' || $_GET['month'] == 'dec')
	  {
               $c_year = $_GET['year'];
               if ($_GET['month'] == 'jan')
               {
                   $t_month = 1;
               }
               else if ($_GET['month'] == 'feb')
               {
                   $t_month = 2;
               }
               else if ($_GET['month'] == 'mar')
               {
                   $t_month = 3;
               }
               else if ($_GET['month'] == 'apr')
               {
                   $t_month = 4;
               }
               else if ($_GET['month'] == 'may')
               {
                   $t_month = 5;
               }
               else if ($_GET['month'] == 'jun')
               {
                   $t_month = 6;
               }
               else if ($_GET['month'] == 'jul')
               {
                   $t_month = 7;
               }
               else if ($_GET['month'] == 'aug')
               {
                   $t_month = 8;
               }
               else if ($_GET['month'] == 'sept')
               {
                   $t_month = 9;
               }
               else if ($_GET['month'] == 'oct')
               {
                   $t_month = 10;
               }
               else if ($_GET['month'] == 'nov')
               {
                   $t_month = 11;
               }
               else if ($_GET['month'] == 'dec')
               {
                   $t_month = 12;
               }
               $c_month = $t_month;
               $c_day = date("d");
        }
        else
        {
               $c_year = date("Y");
               $c_month = date("m");
               $c_day = date("d");
        }
    }
    else
    {
        $c_year = date("Y");
        $c_month = date("m");
        $c_day = date("d");
    }

    $first_weekday = date("w",mktime(0,0,0,$c_month,1,$c_year));
    $first_weekday = ($first_weekday==0?7:$first_weekday);

    $days_in_month = date("t", mktime (0,0,0,$c_month,1,$c_year));

    $calendar_body = '<table border="0" cellspacing="1" cellpadding="0" width="100%">';
    $calendar_body .= '<tr>
	<td height="25" style="border: 1px solid #D6DED5;background-color:#E4EBE3;" colspan="7" align="center" valign="middle">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td align="center" valign="middle" width="15%">' . prev_link($c_year,$c_month) . '</td>
		<td align="center" valign="middle" width="70%">'.$name_months[(int)$c_month].' '.$c_year.'</td>
		<td align="center" valign="middle" width="15%">' . next_link($c_year,$c_month) . '</td>
		</tr>
		</table>
	</td>
	</tr>';

    $calendar_body .= '<tr>';
    for($i=1;$i<=7;$i++) $calendar_body .= '<td style="font-size:0.8em; border: 1px solid #DFE6DE; background-color:#EBF2EA;'.($i<6?'':'color:red;').'" align="center" valign="middle" width="25" height="25">'.$name_days[$i].'</td>';
    $calendar_body .= '</tr>';

    for($i=1;$i<=$days_in_month;){
        $calendar_body .= '<tr>';
        for($ii=1;$ii<=7;$ii++){
            if($ii==$first_weekday AND $i==1) $go = TRUE;
            elseif($i > $days_in_month ) $go = FALSE;

            if($go) $calendar_body .= '<td align="left" valign="top" width="60" height="60" style="'.(date("Ymd", mktime (0,0,0,$c_month,$i,$c_year))==date("Ymd")?'border: 1px solid #BFBFBF;background-color:#E4EBE3;':'border: 1px solid #DFE6DE;').'"><span style="'.($ii<6?'':'color:red;').'">'.$i++.'</span><span style="font-size:0.7em;">' . grab_events($c_year,$c_month,($i-1)) . '</span></td>';
            else $calendar_body .= '<td width="60" height="60" style="border: 1px solid #E9F0E8;">&nbsp;</td>';
        }
        $calendar_body .= '</tr>';
    }
    $calendar_body .= '</table>';
    $calendar_body .= '<div style="font-size:0.7em; text-align:center; padding:2px;">Want your own calendar like this one? Download it from <a href="http://www.kieranoshea.com/programming">Kieran O\'Shea</a></div>';

    return $calendar_body;
}

?>
