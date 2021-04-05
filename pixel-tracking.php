<?php
if(!is_user_logged_in()) {
	if(isset($_REQUEST['utm_source'])) {
		
		if($_REQUEST['utm_source'] == 'comefollowme') {
			?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=UA-165691891-4"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());
			
			  gtag('config', 'UA-165691891-4');
			</script>
			<!-- End Google Analytics -->
			<?php
		}
		
		if($_REQUEST['utm_source'] == 'vienieseguimi') {
			?>			
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=UA-165691891-5"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());
			
			  gtag('config', 'UA-165691891-5');
			</script>
			<!-- End Google Analytics -->
			<?php
		}
		
		if($_REQUEST['utm_source'] == 'viensetsuismoi') {
			?>			
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=UA-165691891-6"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());
			
			  gtag('config', 'UA-165691891-6');
			</script>
			<!-- End Google Analytics -->
			<?php
		}
		
	} else {
		
		if(CFM_URL == 'https://comefollowme.it') {
			?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=UA-165691891-4"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());
			
			  gtag('config', 'UA-165691891-4');
			</script>
			<!-- End Google Analytics -->
			<?php
		} 
	
		if(CFM_URL == 'https://vienieseguimi.it') {
			?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=UA-165691891-5"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());
			
			  gtag('config', 'UA-165691891-5');
			</script>
			<!-- End Google Analytics -->
			<?php
		}
	
		if(CFM_URL == 'https://viensetsuismoi.it') {
			?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=UA-165691891-6"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());
			
			  gtag('config', 'UA-165691891-6');
			</script>
	<!-- End Google Analytics -->
			<?php
		}
	}
}
?>