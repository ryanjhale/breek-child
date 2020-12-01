<!-- Share Modal -->
	
	<style>
		
		.modal {
		    background-image: linear-gradient(rgb(35, 79, 71) 0%, rgb(36, 121, 106) 100.2%)
		}
		
		.modal-title {
		    font-weight: 900
		}
		
		.modal-content {
		    border-radius: 13px
		}
		
		.modal-body {
		    color: #3b3b3b
		}
		
		.img-thumbnail {
		    border-radius: 33px;
		    width: 61px;
		    height: 61px
		}
		
		.fab:before, .fas:before {
		    position: relative;
		    top: 13px
		}
		
		.smd {
		    width: 200px;
		    font-size: small;
		    text-align: center
		}
		
		.modal-footer {
		    display: block
		}
		
		.ur {
		    border: none;
		    background-color: #e6e2e2;
		    border-bottom-left-radius: 4px;
		    border-top-left-radius: 4px
		}
		
		.cpy {
		    border: none;
		    background-color: #e6e2e2;
		    border-bottom-right-radius: 4px;
		    border-top-right-radius: 4px;
		    cursor: pointer
		}
		
		button.focus,
		button:focus {
		    outline: 0;
		    box-shadow: none !important
		}
		
		.ur.focus,
		.ur:focus {
		    outline: 0;
		    box-shadow: none !important
		}
		
		.message {
		    font-size: 11px;
		    color: #ee5535
		}
		
	</style>
	
	<div class="modal fade" id="share-modal" tabindex="-1" role="dialog" aria-labelledby="share-modal" aria-hidden="true" style="display: none;">
	    <div class="modal-dialog modal-dialog-centered" role="document">
	        <div class="modal-content col-12">
	            <div class="modal-header">
	                <h5 class="modal-title">Share</h5> <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
	            </div>
	            <div class="modal-body">
	                <div class="icon-container1 d-flex">
	                    <div class="smd">
		                    <a target="_blank" href="http://twitter.com/share?text=<?php echo urlencode($post->post_title); ?>&url=<?php echo get_permalink($post_id); ?>">
			                    <i class=" img-thumbnail fab fa-twitter fa-2x" style="color:#4c6ef5;background-color: aliceblue"></i>
		                        <p>Twitter</p>
		                    </a>
	                    </div>
	                    <div class="smd">
		                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink($post_id); ?>">
			                    <i class="img-thumbnail fab fa-facebook fa-2x" style="color: #3b5998;background-color: #eceff5;"></i>
		                        <p>Facebook</p>
		                    </a>
	                    </div>
	                    <div class="smd">
		                    <a target="_blank" href="https://api.whatsapp.com/send?text=<?php echo get_permalink($post_id); ?>">
			                    <i class="img-thumbnail fab fa-whatsapp fa-2x" style="color: #3b5998;background-color: #eceff5;"></i>
		                        <p>WhatsApp</p>
		                    </a>
	                    </div>
						<div class="smd">
		                    <a target="_blank" href="mailto:?subject=<?php echo urlencode($post->post_title); ?>&body=<?php echo get_permalink($post_id); ?>">
			                    <i class="img-thumbnail fas fa-envelope fa-2x" style="color: #3b5998;background-color: #eceff5;"></i>
		                        <p>Email</p>
		                    </a>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>