<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Grab</li>
              </ul>
             
			  <section class="panel panel-default">
                <header class="panel-heading font-bold">
                  Grab multi playlist Youtube
                </header>
                <div class="panel-body">
                  <form class="form-horizontal" method="post" action="index.php?act=video&mode=multi">
				  <div class="form-group">
                      <label class="col-sm-2 control-label">Danh sách</label>
					  <div class="col-sm-10"> <select name="webgrab" class="form-control m-b">
<option value="playlist">Playlists</option>
<option value="search">Search</option>
								</select></div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div> 
					<div class="form-group">
                      <label class="col-sm-2 control-label">Text:</label>
					  <div class="col-sm-10"><input type="text" name="urlgrab" size="50" value="" class="form-control rounded"></div>
                    </div>
                   
                    <div class="line line-dashed line-lg pull-in"></div>
<div class="form-group">
                      <label class="col-sm-2 control-label">Danh mục</label>
					  <div class="col-sm-10"> <select name="danhmuc" class="form-control m-b">
<?=cat_video_show();?>
								</select></div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div> 
<div class="form-group">
                      <label class="col-sm-2 control-label">Max items:</label>
					  <div class="col-sm-10"><input type="text" name="maxitem" size="50" value="" class="form-control rounded"></div>
                    </div>
                   
                    <div class="line line-dashed line-lg pull-in"></div>
<div class="form-group">
                      <div class="col-sm-4 col-sm-offset-2">
                        <button type="submit" name="ok" class="btn btn-primary">Grab</button>
                      </div>
                    </div>
                  </form>
                </div>
              </section>
			 </section>
          </section>