<div class="row">
    <div class="col-md-12 mb-3">
        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-table me-2"></i></span> Orders
            </div>
            <div class="card-body">

                <div class="d-flex justify-content-center">
                    <div class="ajax-loading spinner-border text-primary m-5" role="status" id="loading-orders">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped data-table" id="orders-list" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Cost</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>