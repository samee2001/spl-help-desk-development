<div class="modal fade" id="editRuleModal" tabindex="-1" aria-labelledby="editRuleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRuleModalLabel">Edit Rule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRuleForm">
                        <div class="mb-3">
                            <label for="ruleLabelInput" class="form-label text-muted small">Rule label</label>
                            <input type="text" class="form-control form-control-lg" id="ruleLabelInput" >
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Rules</h6>
                            <select class="form-select mb-3">
                                <option selected>Any</option>
                                <option value="1">All</option>
                            </select>

                            <div class="row g-2 align-items-center mb-3">
                                <div class="col-md-4">
                                    <select class="form-select">
                                        <option selected>Summary</option>
                                        <option value="1">Description</option>
                                        <option value="2">Category</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select">
                                        <option selected>is</option>
                                        <option value="1">contains</option>
                                        <option value="2">starts with</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="Proposal Login">
                                </div>
                                <div class="col-md-1 d-flex justify-content-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger border-0 text-secondary delete-rule-row">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="row g-2 align-items-center mb-3">
                                <div class="col-md-4">
                                    <select class="form-select">
                                        <option selected>Summary</option>
                                        <option value="1">Description</option>
                                        <option value="2">Category</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select">
                                        <option selected>contains</option>
                                        <option value="1">is</option>
                                        <option value="2">starts with</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="Proposal Login">
                                </div>
                                <div class="col-md-1 d-flex justify-content-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger border-0 text-secondary delete-rule-row">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>

                            <a href="#" class="text-decoration-none text-primary d-block mt-3 add-rule-btn-modal">
                                <i class="fas fa-plus"></i> Add rule
                            </a>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted mb-3">Actions</h6>
                            <a href="#" class="text-decoration-none text-primary d-block mt-3 add-action-btn-modal">
                                <i class="fas fa-plus"></i> Add action
                            </a>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>