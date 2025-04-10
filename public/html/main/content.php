
    <div id="wrapper">
    <div class="container-fluid" id="body-wrapper<?php if(NIVO0_NICENAME == 'login') echo '-login';?>">
        <div class="row container-row">
        <?php
        if(NIVO0_NICENAME != 'login')
        {
            //d-none d-lg-block
            ?>
            <div class="col-lg-2  menu-part">
            
            <div id="sidebar"><div id="sidebar-wrapper">
                <!-- Sidebar Profile links -->
                <div id="profile-links"> 
       
                    <nav class="navbar navbar-inverse navbar-fixed-top" style="padding:0;">
                        <div class="navbar-header" style="width:100%; margin:10px 0;">
                            <!-- <a class="navbar-brand" style="float:left;" href="#"><img src="/public/resources/images/hkl_logo.png" style="width:100px;" /></a> -->
                            <a class="navbar-brand" style="float:left;" href="#"><img src="/public/resources/images/orka_logo.png" style="width: 117px; height:31px" /></a>
                            <button type="button" style="float:right; margin-left:10px;" class="navbar-toggle d-none  d-block d-lg-none" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="icon-reorder icon-3x"></span>
                            </button>
                            <div style="float:right;">
                                Pozdravljeni, <a href="#" title="Edit your profile"><?=$this->user->get_property('ime_priimek')?></a><br />
                                <a href="<?php echo MAIN_PAGE; ?>" target="_blank" title="Poglej spletno mesto">Spletno mesto</a> | <a href="/logout" title="Odjava">Odjava</a>
                            </div>
                        </div>
                        <div class="navbar-collapse collapse">
                            <div class="nav navbar-nav">
                            <?php
                                $user_id = $this->user->get_property('id');
                                $this->base->mainMenuCMS2(0, $user_id);
                            ?>
                            <?php if($user_id == 96 || $user_id == 94): ?>
                                Test
                            <?php endif; ?>
                            </div>
                        </div>

                        <!-- test -->
                    </nav>
                </div>
                
                </div></div>
            </div>
            <div class="col-lg-10 col-md-12">
            <div id="main-content">
              <?php $this->Controller(); ?>
              <div id="magic_box_edit" class="content-box hide"></div>
          </div>
          </div>
          <?php
        }
        else $this->Controller();
        ?>

        </div>
    </div>
</div>
