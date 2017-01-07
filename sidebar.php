           <div class="<?php liquid_col_options('sidebar'); ?> sidebar">
             <div class="widgets">
                <?php if(! dynamic_sidebar('sidebar')){ ?>
                 <!-- no widget -->
                <?php } ?>
             </div>
           </div>