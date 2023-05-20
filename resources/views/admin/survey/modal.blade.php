<!-- Modal -->
<div class="modal fade" id="modal{{ $modal_id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-form-label">テキスト:</label>
                    <textarea type="text" class="form-control" id="txt{{ $modal_id }}"></textarea>
                </div>
                <div class="form-group">
                    <label  class="col-form-label">タイプ:</label>
                    <select class="form-control" id="opt{{ $modal_id }}">
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                <button type="button" class="btn btn-primary" id="btn{{ $modal_id }}">追加</button>
            </div>
            <input type="hidden" id="container-id">
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAddSubQuestion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-form-label">テキスト:</label>
                    <textarea type="text" class="form-control" id="txtAddSubQuestion"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                <button type="button" class="btn btn-primary" id="btnAddSubQuestion">追加</button>
            </div>
            <input type="hidden" id="sub-container-id">
        </div>
    </div>
</div>
