<div class="container container-small">
   <div class="row" style="margin-top:100px;">
      <a class="btn btn-default float-right" href="#" onclick="history.back(-1)"style="margin-bottom:10px;">
         <i class="fa fa-arrow-left"></i> <?=label("Back");?></a>
      <?php echo form_open_multipart('settings/editRol/'.$rol->id); ?>

            <div class="form-group">
            <label for="txtNombreRol"><?= label("Nombre Rol"); ?> *</label>
             <input type="text" name="name" value="<?=$rol->name?>" class="form-control" id="txtNombreRol" placeholder="<?=label("Nombre Rol");?>">
           </div>
           <div class="form-group">
           <label for="txtDescripcionRol"><?= label("Descripción"); ?></label>
             <input type="text" name="description" value="<?=$rol->description?>" class="form-control" id="txtDescripcionRol" placeholder="<?=label("firstname");?>">
           </div>
      <div class="form-group">
        <button type="submit" class="btn btn-green col-md-6 flat-box-btn"><?=label("Submit");?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
</div>
