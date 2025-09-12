<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>

</style>

<body>
    <div class="container-fluid p-0">
        <!-- Header -->
        <div class="mb-3">
            <?php include 'components/nav_bar.php'; ?>
        </div>
        <div class="row g-0">
            <!-- Sidebar -->
            <?php include 'components/side_bar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-10 p-4">
                <h1 class="mb-4">Settings</h1>
                <h4>Ticket Rules</h4>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <h1 class="h3 mb-0">Ticket rules</h1> <button class="btn btn-primary">Add ticket rule</button>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="fw-normal">Proposal Login</span>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="toggleProposalLogin" checked>
                                    <label class="form-check-label visually-hidden" for="toggleProposalLogin">Toggle Proposal Login</label>
                                </div>
                                <a href="#" class="text-decoration-none text-primary edit-rule-btn" data-bs-toggle="modal" data-bs-target="#editRuleModal" data-rule-label="Proposal Login">Edit</a>
                                <a href="#" class="text-decoration-none text-danger">Delete</a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="fw-normal">Refund</span>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="toggleRefund" checked>
                                    <label class="form-check-label visually-hidden" for="toggleRefund">Toggle Refund</label>
                                </div>
                                <a href="#" class="text-decoration-none text-primary edit-rule-btn" data-bs-toggle="modal" data-bs-target="#editRuleModal" data-rule-label="Refund">Edit</a>
                                <a href="#" class="text-decoration-none text-danger">Delete</a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="fw-normal">Reactivation</span>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="toggleReactivation" checked>
                                    <label class="form-check-label visually-hidden" for="toggleReactivation">Toggle Reactivation</label>
                                </div>
                                <a href="#" class="text-decoration-none text-primary">Edit</a>
                                <a href="#" class="text-decoration-none text-danger">Delete</a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="fw-normal">Harvest Payment</span>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="toggleHarvest" checked>
                                    <label class="form-check-label visually-hidden" for="toggleHarvest">Toggle Harvest Payment</label>
                                </div>
                                <a href="#" class="text-decoration-none text-primary">Edit</a>
                                <a href="#" class="text-decoration-none text-danger">Delete</a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="fw-normal">Sales Force</span>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="toggleSalesForce" checked>
                                    <label class="form-check-label visually-hidden" for="toggleSalesForce">Toggle Sales Force</label>
                                </div>
                                <a href="#" class="text-decoration-none text-primary">Edit</a>
                                <a href="#" class="text-decoration-none text-danger">Delete</a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="fw-normal">Payment history</span>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="togglePaymentHistory" checked>
                                    <label class="form-check-label visually-hidden" for="togglePaymentHistory">Toggle Payment history</label>
                                </div>
                                <a href="#" class="text-decoration-none text-primary">Edit</a>
                                <a href="#" class="text-decoration-none text-danger">Delete</a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="fw-normal">Document management</span>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="toggleDocumentManagement" checked>
                                    <label class="form-check-label visually-hidden" for="toggleDocumentManagement">Toggle Document management</label>
                                </div>
                                <a href="#" class="text-decoration-none text-primary">Edit</a>
                                <a href="#" class="text-decoration-none text-danger">Delete</a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="fw-normal">Intranet</span>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="toggleIntranet" checked>
                                    <label class="form-check-label visually-hidden" for="toggleIntranet">Toggle Intranet</label>
                                </div>
                                <a href="#" class="text-decoration-none text-primary">Edit</a>
                                <a href="#" class="text-decoration-none text-danger">Delete</a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="fw-normal">Network</span>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="toggleNetwork" checked>
                                    <label class="form-check-label visually-hidden" for="toggleNetwork">Toggle Network</label>
                                </div>
                                <a href="#" class="text-decoration-none text-primary">Edit</a>
                                <a href="#" class="text-decoration-none text-danger">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </main>
        <?php include 'components/edit_ticket.php'; ?>
    </div>
    </div>
    <script src="edit_ticket_script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>