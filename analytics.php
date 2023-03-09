<?php
if(!is_user_logged_in()) {
		
	if(CFM_ENV == 'prod-english') {
		?>
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-NNHKNN51WG"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		
		  gtag('config', 'G-NNHKNN51WG');
		</script>
		<?php
	}
	
	if(CFM_ENV == 'prod-italian') {
		?>			
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-53QV1T22D4"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		
		  gtag('config', 'G-53QV1T22D4');
		</script>

		<?php
	}
	
	if(CFM_ENV == 'prod-french') {
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
?>