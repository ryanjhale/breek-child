<?php

$url = get_site_url(null, '/', 'https');	

if($url == 'https://comefollowme.it/') {
	$sheet_name = 'Banding Together - EN';
}

if($url == 'https://vienieseguimi.it/') {
	$sheet_name = 'Banding Together - IT';
}

$scriptures = google_spreadsheet_get_data('1-LBAc4y6veOL8iXDbejgVUNusxBVC-fffLCgysDntJc', $sheet_name, $cell_range = '');

if(CFM_ENV == 'prod-english') {
	  	
  	setlocale(LC_ALL, 'en_US');
  	
  	$monday = date( 'l F j', strtotime( 'monday this week' ) );
  	$tuesday = date( 'l F j', strtotime( 'tuesday this week' ) );
	$wednesday = date( 'l F j', strtotime( 'wednesday this week' ) );
	$thursday = date( 'l F j', strtotime( 'thursday this week' ) );
	$friday = date( 'l F j', strtotime( 'friday this week' ) );
	$saturday = date( 'l F j', strtotime( 'saturday this week' ) );
	$sunday = date( 'l F j', strtotime( 'sunday this week' ) );
	
}

if(CFM_ENV == 'prod-italian') {
	  	
  	setlocale(LC_ALL, 'it_IT');
  	
  	$monday = date( 'l j F', strtotime( 'monday this week' ) );
  	$tuesday = date( 'l j F', strtotime( 'tuesday this week' ) );
	$wednesday = date( 'l J F', strtotime( 'wednesday this week' ) );
	$thursday = date( 'l j F', strtotime( 'thursday this week' ) );
	$friday = date( 'l j F', strtotime( 'friday this week' ) );
	$saturday = date( 'l j F', strtotime( 'saturday this week' ) );
	$sunday = date( 'l j F', strtotime( 'sunday this week' ) );
	
}

$monday_month = date( 'n', strtotime( 'monday this week' ) );
$tuesday_month = date( 'n', strtotime( 'tuesday this week' ) );
$wednesday_month = date( 'n', strtotime( 'wednesday this week' ) );
$thursday_month = date( 'n', strtotime( 'thursday this week' ) );
$friday_month = date( 'n', strtotime( 'friday this week' ) );
$saturday_month = date( 'n', strtotime( 'saturday this week' ) );
$sunday_month = date( 'n', strtotime( 'sunday this week' ) );

$monday_day = date( 'j', strtotime( 'monday this week' ) );
$tuesday_day = date( 'j', strtotime( 'tuesday this week' ) );
$wednesday_day = date( 'j', strtotime( 'wednesday this week' ) );
$thursday_day = date( 'j', strtotime( 'thursday this week' ) );
$friday_day = date( 'j', strtotime( 'friday this week' ) );
$saturday_day = date( 'j', strtotime( 'saturday this week' ) );
$sunday_day = date( 'j', strtotime( 'sunday this week' ) );

$week = array('monday' =>
				 array('display' 	=> $monday,
				 	   'month'		=> $monday_month,
				 	   'day'		=> $monday_day
				 	  ),
		      'tuesday' => 
		         array('display'	=> $tuesday,
		               'month'		=> $tuesday_month,
		               'day'		=> $tuesday_day
		               ),
		       'wednesday' => 
		         array('display'	=> $wednesday,
		               'month'		=> $wednesday_month,
		               'day'		=> $wednesday_day
		               ),
		        'thursday' => 
		         array('display'	=> $thursday,
		               'month'		=> $thursday_month,
		               'day'		=> $thursday_day
		               ),
		        'friday' => 
		         array('display'	=> $friday,
		               'month'		=> $friday_month,
		               'day'		=> $friday_day
		               ),
		        'saturday' => 
		         array('display'	=> $saturday,
		               'month'		=> $saturday_month,
		               'day'		=> $saturday_day
		               ),
		        'sunday' => 
		         array('display'	=> $sunday,
		               'month'		=> $sunday_month,
		               'day'		=> $sunday_day
		               ),
		);



?>
<style>
	.scripture-td-left, .scripture-td-right {
		text-align:left;
	}
</style>
<table cellpadding="0" cellspacing="0" width="100%">

<?php                            
foreach($week as $day) {
    $m = $day['month'];
    $d = $day['day'];
    
    ?>
    <tr>
	    <td class="scripture-td-left"><?php echo $day['display']; ?></td>
	    <td class="scripture-td-right"><?php echo '<strong>' . $scriptures[$d][$m] . '</strong>'; ?> </td>
    </tr>
<?php
}
?>
</table>