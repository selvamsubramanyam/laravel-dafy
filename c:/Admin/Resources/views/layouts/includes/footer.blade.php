<!-- footer content -->
<!-- <footer>
          <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
          </div>
          <div class="clearfix"></div>
        </footer> -->
<!-- /footer content -->
</div>
</div>

<!-- jQuery -->
<script src="{{URL('assets/plugins/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{URL('assets/plugins/bootstrap/dist/js/bootstrap.min.js')}}"></script>

<!-- Custom Theme Scripts -->

<script src="{{URL('public/admin/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>

<script src="{{URL('assets/build/js/custom.min.js')}}"></script>

<!-- My Scripts -->
<script src="{{URL('assets/js/custom.js')}}"></script>


<script src="{{URL('public/admin/js/dev.js')}}"></script>

 <script type="text/javascript">
 
 //var base_url="{{url('http://localhost/dafy')}}";
           var base_url="{{url('/')}}";
            var storage_path="{{storage_path()}}";
        </script>

<!-- Modal -->
<div id="edit-1" class="modal fade edit-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit</h4>
            </div>
            <div class="modal-body">
                <textarea id="text-edit"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" id="save-input" class="btn btn-default btn-save"
                    data-dismiss="modal">Save</button>
            </div>
        </div>

    </div>
</div>

<div id="add-categ" class="modal fade edit-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add category</h4>
            </div>
            <div class="modal-body">
                <input type="text" id="categ-name" placeholder="Category Name">
                <select name="status" id="">
                    <option value="" selected>Active</option>
                    <option value="" selected>Inactive</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" id="save-input" class="btn btn-default btn-save"
                    data-dismiss="modal">Save</button>
            </div>
        </div>

    </div>
</div>

<!-- Edit Category Name -->
<div id="edt-categ-name" class="modal fade edit-modal" role="dialog">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center  modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title caption-normal">Edit Category Name</h4>
                </div>
                <div class="modal-body">
                    <div class="row mdl-table">
                        <form action="">
                            <table class="table">
                                <tr>
                                    <td>
                                        <label for="">Category Name</label>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-border">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="save-input" class="btn btn-default btn-save"
                        data-dismiss="modal">Save</button>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Edit Category Name END -->



<!-- Add Sub Category Modal -->
<div id="add-prod" class="modal fade edit-modal" role="dialog">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center  modal-md">
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title caption-normal">Add Sub-category</h4>
                </div>
                <div class="modal-body">
                    <div class="row mdl-table">
                        <form action="">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="label-left">
                                            <label for="">Sub-category Name</label>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control form-border">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-left">
                                            <label for="">Status</label>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select name="" id="" class="form-control form-border">
                                                    <option value="">Active</option>
                                                    <option value="">Inctive</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="label-left">
                                            <label for="">Filters</label>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control form-border">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-left">
                                            <label for="">Questions</label>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                               <textarea name="" id="" rows="5" class="form-control form-border"></textarea>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <!-- <input type="text" id="prod-name" placeholder="Product Name">
                    <select name="status" id="">
                        <option value="" selected>Active</option>
                        <option value="" selected>Inactive</option>
                    </select> -->
                </div>
                <div class="modal-footer">
                    <button type="button" id="save-input" class="btn btn-default btn-save"
                        data-dismiss="modal">Save</button>
                </div>
            </div>
            <!-- Modal content END-->
        </div>
    </div>
</div>
<!-- Add Sub Category Modal END -->


<div id="add-exec" class="modal fade edit-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Executive</h4>
            </div>
            <div class="modal-body">
                <input type="text" id="name" placeholder="Name">
                <input type="text" id="addr" placeholder="Address">
                <input type="text" id="loc" placeholder="location">
                <input type="text" id="contact" placeholder="Contact Number">
                <input type="text" id="id" placeholder="Id Proof number">
                <select name="status" id="">
                    <option value="" selected>Active</option>
                    <option value="" selected>Inactive</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" id="save-input" class="btn btn-default btn-save"
                    data-dismiss="modal">Save</button>
            </div>
        </div>

    </div>
</div>

</body>

</html>