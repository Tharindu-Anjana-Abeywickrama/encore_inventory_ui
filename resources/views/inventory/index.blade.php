@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0 fw-bold">Inventory</h1>
        <div class="d-flex align-items-center gap-3">
         
        </div>
    </div>

    <!-- Filters and Actions Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="d-flex gap-3">
                <div>
                    <select class="form-select" id="productFilter" style="min-width: 150px;">
                        <option value="">Number of Product | All</option>
                        <option value="1-10">1-10</option>
                        <option value="11-50">11-50</option>
                        <option value="51-100">51-100</option>
                        <option value="100+">100+</option>
                    </select>
                </div>
                <div>
                    <select class="form-select" id="totalFilter" style="min-width: 150px;">
                        <option value="">Total Product | All</option>
                        <option value="active">Active</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end gap-2">
                <div class="position-relative mt-2" style="min-width: 300px;">
                    <input type="text" class="form-control" placeholder="Search" id="searchInput">
                    <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                </div>
                <button class="btn btn-light border me-2" id="exportBtn">
                    <i class="fas fa-download me-1"></i> Export
                </button>
                <button class="btn btn-light border me-2" id="importBtn">
                    <i class="fas fa-upload me-1"></i> Import
                </button>
              
            </div>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="mb-4">
        <ul class="nav nav-tabs" id="statusTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">All</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">Active</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="draft-tab" data-bs-toggle="tab" data-bs-target="#draft" type="button" role="tab">Draft</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="achieved-tab" data-bs-toggle="tab" data-bs-target="#achieved" type="button" role="tab">Achieved</button>
            </li>
        </ul>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="inventoryTable">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th>Product <i class="fas fa-sort ms-1 text-muted"></i></th>
                            <th>Status <i class="fas fa-sort ms-1 text-muted"></i></th>
                            <th>Inventory <i class="fas fa-sort ms-1 text-muted"></i></th>
                            <th>Sales channels <i class="fas fa-sort ms-1 text-muted"></i></th>
                            <th>Markets <i class="fas fa-sort ms-1 text-muted"></i></th>
                            <th>Category <i class="fas fa-sort ms-1 text-muted"></i></th>
                            <th>Vendor <i class="fas fa-sort ms-1 text-muted"></i></th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination Info -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted">
            <span id="paginationInfo">Showing 1-50 of 931 results</span>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0" id="pagination">
                <!-- Pagination will be generated by DataTable -->
            </ul>
        </nav>
    </div>
</div>



<!-- File Gallery Modal -->
<div class="modal fade" id="fileGalleryModal" tabindex="-1" aria-labelledby="fileGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileGalleryModalLabel">Product Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Product: <span id="galleryProductName" class="fw-bold"></span></label>
                </div>
                <div id="fileGallery" class="row">
                    <!-- Files will be loaded here -->
                </div>
                <div id="noFilesMessage" class="text-center py-4" style="display: none;">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No files uploaded for this product yet.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addMoreFilesBtn">Add More Files</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select Products</label>
                    <div class="row" id="productSelection">
                        <!-- Products will be loaded here -->
                    </div>
                </div>
                <form id="bulkUploadForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="bulkFiles" class="form-label">Select Files</label>
                        <input type="file" class="form-control" id="bulkFiles" name="files[]" multiple accept="image/*" required>
                        <div class="form-text">You can select up to 50 files. Files will be distributed among selected products.</div>
                    </div>
                    <div class="progress mb-3" style="display: none;" id="bulkUploadProgress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <div id="bulkUploadStatus" class="alert" style="display: none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="bulkUploadFilesBtn">Upload Files</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    /* Custom styles for the inventory table */
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        padding: 1rem 0.75rem;
    }
    
    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-top: 1px solid #dee2e6;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    /* Variant rows styling */
    .variant-header-row {
        background-color: #f8f9fa !important;
        font-weight: 600;
        color: #495057;
    }
    
    .variant-subheader-row {
        background-color: #e9ecef !important;
        font-weight: 600;
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .variant-row {
        background-color: #f8f9fa !important;
    }
    
    .variant-row td:first-child {
        padding-left: 2rem !important;
    }
    
    /* Status badges */
    .badge-status {
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .badge-draft {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    .badge-active {
        background-color: #d4edda;
        color: #155724;
    }
    
    /* Product image styling */
    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
    }
    
    .product-placeholder {
        width: 50px;
        height: 50px;
        background-color: #e9ecef;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        border: 1px solid #dee2e6;
    }
    
    /* Variant image styling */
    .variant-image {
        width: 35px;
        height: 35px;
        object-fit: cover;
        border-radius: 0.25rem;
        border: 1px solid #dee2e6;
    }
    
    .variant-placeholder {
        width: 35px;
        height: 35px;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        border: 1px solid #dee2e6;
        font-size: 0.75rem;
    }
    
    /* Gallery image styling */
    .gallery-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 0.375rem;
    }
    
    /* Main product image in modal */
    .modal-product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 2px solid #dee2e6;
    }
    
    /* Thumbnail images */
    .thumbnail-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    
    .thumbnail-image:hover {
        transform: scale(1.05);
        border-color: #0d6efd;
    }
    
    /* File upload preview images */
    .upload-preview-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
        margin: 0.25rem;
    }
    
    /* Image loading states */
    .image-loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Image error handling */
    .image-error {
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 0.75rem;
    }
    
    /* Responsive image containers */
    .image-container {
        position: relative;
        overflow: hidden;
        border-radius: 0.375rem;
    }
    
    .image-container img {
        transition: transform 0.3s ease;
    }
    
    .image-container:hover img {
        transform: scale(1.05);
    }
    
    /* Image aspect ratio maintenance */
    .aspect-ratio-1-1 {
        aspect-ratio: 1 / 1;
    }
    
    .aspect-ratio-4-3 {
        aspect-ratio: 4 / 3;
    }
    
    .aspect-ratio-16-9 {
        aspect-ratio: 16 / 9;
    }
    
    /* Color indicator for variants */
    .color-indicator {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
        border: 1px solid #dee2e6;
    }
    
    /* Notification badge */
    .notification-badge {
        position: relative;
    }
    
    .badge-counter {
        position: absolute;
        top: -5px;
        right: -5px;
        font-size: 0.6rem;
        padding: 0.2rem 0.4rem;
    }
    
    /* Search input styling */
    #searchInput {
        padding-right: 2.5rem;
    }
    
    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }
    
    .pagination .page-link {
        color: #495057;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
    }
    
    .pagination .page-link:hover {
        color: #0d6efd;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    /* Action buttons */
    .btn-action {
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
        border-width: 1.5px;
    }
    
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .btn-action.btn-outline-success {
        border-color: #28a745;
        color: #28a745;
    }
    
    .btn-action.btn-outline-success:hover {
        background-color: #28a745;
        color: white;
    }
    
    .btn-action.btn-outline-info {
        border-color: #17a2b8;
        color: #17a2b8;
    }
    
    .btn-action.btn-outline-info:hover {
        background-color: #17a2b8;
        color: white;
    }
    
    .btn-action.btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
    }
    
    .btn-action.btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }
    
    /* Export/Import buttons */
    .btn-light {
        border: 1.5px solid #dee2e6;
        transition: all 0.2s ease;
    }
    
    .btn-light:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
        transform: translateY(-1px);
    }
    
    .btn-outline-success {
        border-width: 1.5px;
        transition: all 0.2s ease;
    }
    
    .btn-outline-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
    }
    
    /* Loading spinner */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #0d6efd;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let inventoryTable;
    let currentProductId = null;
    
    // Initialize DataTable with server-side processing
    inventoryTable = $('#inventoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('inventory.data') }}",
            type: 'GET',
            data: function(d) {
                d.status_filter = $('#statusTabs .nav-link.active').attr('id').replace('-tab', '');
                d.product_filter = $('#productFilter').val();
                d.total_filter = $('#totalFilter').val();
            }
        },
        columns: [
            {
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<div class="form-check"><input class="form-check-input" type="checkbox" value="' + row.id + '"></div>';
                }
            },
            {
                data: 'product',
                name: 'product',
                render: function(data, type, row) {
                    let image = row.main_image ? 
                        '<img src="' + row.main_image + '" class="product-image me-3" alt="' + row.name + '" width="50" height="50" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">' +
                        '<div class="product-placeholder me-3" style="display: none;"><i class="fas fa-box"></i></div>' :
                        '<div class="product-placeholder me-3"><i class="fas fa-box"></i></div>';
                    
                    return '<div class="d-flex align-items-center">' + 
                           image + 
                           '<div><div class="fw-bold">' + row.name + '</div>' +
                           '<div class="small text-muted">SKU: ' + (row.sku || row.id) + '</div></div></div>';
                }
            },
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row) {
                    let statusClass = row.status == 1 ? 'badge-active' : 'badge-draft';
                    let statusText = row.status == 1 ? 'Active' : 'Draft';
                    return '<span class="badge-status ' + statusClass + '">' + statusText + '</span>';
                }
            },
            {
                data: 'inventory',
                name: 'inventory',
                render: function(data, type, row) {
                    let stockCount = row.total_stock || 0;
                    let variantCount = row.variant_count || 0;
                    let inventoryText = stockCount + ' In Stock';
                    
                    if (variantCount > 0) {
                        inventoryText += ' For ' + variantCount + ' Variants';
                    }
                    
                    let lastUpdate = stockCount > 0 ? 
                        '<div class="small text-muted">Last Update - <a href="#" class="text-primary">25 AUG 25</a></div>' : '';
                    
                    return inventoryText + lastUpdate;
                }
            },
            {
                data: 'sales_channels',
                name: 'sales_channels',
                render: function(data, type, row) {
                    return row.sales_channels || 0;
                }
            },
            {
                data: 'markets',
                name: 'markets',
                render: function(data, type, row) {
                    return row.markets || 0;
                }
            },
            {
                data: 'category',
                name: 'category',
                render: function(data, type, row) {
                    if (row.categories && row.categories.length > 0) {
                        return row.categories.map(cat => 
                            '<span class="badge bg-light text-dark me-1">' + cat.name + '</span>'
                        ).join('');
                    }
                    return '<span class="badge bg-light text-dark">No Category</span>';
                }
            },
            {
                data: 'vendor',
                name: 'vendor',
                render: function(data, type, row) {
                    if (row.vendors && row.vendors.length > 0) {
                        return row.vendors.map(vendor => vendor.name).join(', ');
                    }
                    return 'No Vendor';
                }
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    let variantCount = row.variant_count || 0;
                    let actions = '';
                    
                    if (variantCount > 0) {
                        actions += '<button class="btn btn-sm btn-outline-primary btn-action me-1 toggle-variants-btn" ' +
                                  'data-product-id="' + row.id + '" title="Show Variants">' +
                                  '<i class="fas fa-eye"></i></button>';
                    }
                    
                   
                    
                    return actions;
                }
            }
        ],
        order: [[1, 'asc']],
        pageLength: 50,
        lengthMenu: [[25, 50, 100, 200], [25, 50, 100, 200]],
        responsive: true,
        language: {
            processing: '<div class="loading-spinner"></div> Loading...',
            search: "",
            searchPlaceholder: "Search inventory...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        drawCallback: function(settings) {
            // Update pagination info
            let info = this.api().page.info();
            $('#paginationInfo').text('Showing ' + (info.start + 1) + '-' + info.end + ' of ' + info.recordsTotal + ' results');
            
            // Initialize custom functionality
            initializeCustomFeatures();
        },
        initComplete: function() {
            // Custom search functionality
            $('#searchInput').on('keyup', function() {
                inventoryTable.search(this.value).draw();
            });
        }
    });
    
    // Status tab filtering
    $('#statusTabs .nav-link').on('click', function() {
        $('#statusTabs .nav-link').removeClass('active');
        $(this).addClass('active');
        inventoryTable.ajax.reload();
    });
    
    // Product and total filters
    $('#productFilter, #totalFilter').on('change', function() {
        inventoryTable.ajax.reload();
    });
    
    // Select all functionality
    $('#selectAll').on('click', function() {
        let isChecked = $(this).prop('checked');
        $('.form-check-input:not(#selectAll)').prop('checked', isChecked);
        
        // Handle variant display based on select all
        if (isChecked) {
            // Show all variants
            showAllVariants();
        } else {
            // Hide all variants
            hideAllVariants();
        }
    });
    
    // Individual checkbox functionality
    $(document).on('change', '.form-check-input:not(#selectAll)', function() {
        let productId = $(this).val();
        let isChecked = $(this).prop('checked');
        
        if (isChecked) {
            // Hide all other variants first
            hideAllVariants();
            // Show variants for selected product
            showProductVariants(productId);
        } else {
            // Hide variants for this product
            hideProductVariants(productId);
        }
    });
    
    // Toggle variants button
    $(document).on('click', '.toggle-variants-btn', function() {
        let productId = $(this).data('product-id');
        let $button = $(this);
        let $icon = $button.find('i');
        let $checkbox = $('.form-check-input[value="' + productId + '"]');
        
        // Check if variants are currently visible
        if ($('.variant-row[data-product-id="' + productId + '"]').is(':visible')) {
            hideProductVariants(productId);
            $checkbox.prop('checked', false);
        } else {
            hideAllVariants();
            showProductVariants(productId);
            $checkbox.prop('checked', true);
        }
    });
    
    // Show all variants function
    function showAllVariants() {
        // This would need to be implemented with AJAX to load variant data
        // For now, we'll just update the button states
        $('.variant-header-row').hide();
        $('.toggle-variants-btn i').removeClass('fa-eye').addClass('fa-eye-slash');
        $('.toggle-variants-btn').attr('title', 'Hide Variants');
    }
    
    // Hide all variants function
    function hideAllVariants() { 
        $('.variant-header-row').hide();
        $('.variant-row, .variants-header-row, .variant-subheader-row').hide();
        $('.toggle-variants-btn i').removeClass('fa-eye-slash').addClass('fa-eye');
        $('.toggle-variants-btn').attr('title', 'Show Variants');
        $('.form-check-input:not(#selectAll)').prop('checked', false);
    }
    
    // Show variants for specific product
    function showProductVariants(productId) {
        // Load variant data via AJAX
        loadProductVariants(productId);
    }
    
    // Hide variants for specific product
    function hideProductVariants(productId) {
        $('.variant-row[data-product-id="' + productId + '"], .variants-header-row[data-product-id="' + productId + '"], .variant-subheader-row[data-product-id="' + productId + '"]').hide();
        $('.toggle-variants-btn[data-product-id="' + productId + '"] i').removeClass('fa-eye-slash').addClass('fa-eye');
        $('.toggle-variants-btn[data-product-id="' + productId + '"]').attr('title', 'Show Variants');
    }
    
    // Load product variants via AJAX
    function loadProductVariants(productId) {
        $.ajax({
            url: "{{ url('inventory') }}/" + productId + "/variants",
            type: 'GET',
            success: function(response) {
                if (response.success && response.variants.length > 0) {
                    displayVariants(productId, response.variants);
                }
            },
            error: function() {
                console.error('Failed to load variants for product ' + productId);
            }
        });
    }
    
    // Display variants in the table
    function displayVariants(productId, variants) {
        let $tableBody = $('#inventoryTable tbody');
        let $productRow = $tableBody.find('tr').filter(function() {
            return $(this).find('.form-check-input').val() == productId;
        });
        
        // Find the position to insert variant rows
        let insertIndex = $productRow.index() + 1;
        
        // Remove existing variant rows for this product
        $('.variant-row[data-product-id="' + productId + '"], .variants-header-row[data-product-id="' + productId + '"], .variant-subheader-row[data-product-id="' + productId + '"]').remove();
        
        // Create variants header row
        let headerRow = '<tr class="variant-header-row" data-product-id="' + productId + '">' +
                       '<td></td><td class="fw-bold">Variants</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        
        // Create variant subheader row
        let subheaderRow = '<tr class="variant-subheader-row" data-product-id="' + productId + '">' +
                          '<td></td><td class="ps-4 fw-bold">Size</td><td class="fw-bold">Stock</td><td class="fw-bold">Prices</td>' +
                          '<td class="fw-bold">Discount</td><td></td><td></td><td></td><td></td></tr>';
        
        // Insert header rows
        $productRow.after(headerRow + subheaderRow);
        
        // Create variant rows
        variants.forEach(function(variant) {
            let colorIndicator = variant.color ? 
                '<div class="color-indicator me-2" style="background-color: ' + variant.color + '"></div>' : '';
            
            let variantImage = variant.image ? 
                '<img src="' + variant.image + '" class="variant-image me-2" alt="' + variant.name + '" width="35" height="35" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">' +
                '<div class="variant-placeholder me-2" style="display: none;"><i class="fas fa-image"></i></div>' :
                '<div class="variant-placeholder me-2"><i class="fas fa-image"></i></div>';
            
            let variantRow = '<tr class="variant-row" data-product-id="' + productId + '">' +
                            '<td></td>' +
                            '<td class="ps-4"><div class="d-flex align-items-center">' + variantImage + colorIndicator + '<span class="fw-medium">' + variant.name + '</span></div></td>' +
                            '<td><div class="fw-medium">' + (variant.stock || 0) + ' In Stock For ' + variant.name + '</div>' +
                            '<div class="small text-muted">Last Update - <a href="#" class="text-primary">25 AUG 25</a></div></td>' +
                            '<td><div class="fw-medium text-success">$' + parseFloat(variant.price || 0).toFixed(2) + '</div></td>' +
                            '<td><div class="small text-muted">0%</div></td>' +
                            '<td></td><td></td><td></td>' +
                            '<td><button class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-eye"></i></button></td>' +
                            '</tr>';
            
            $('.variant-subheader-row[data-product-id="' + productId + '"]').after(variantRow);
        });
        
        // Update button state
        $('.toggle-variants-btn[data-product-id="' + productId + '"] i').removeClass('fa-eye').addClass('fa-eye-slash');
        $('.toggle-variants-btn[data-product-id="' + productId + '"]').attr('title', 'Hide Variants');
    }
    
    // Initialize custom features function
    function initializeCustomFeatures() {
        // Handle image loading errors
        $('.product-image, .variant-image, .gallery-image').on('error', function() {
            $(this).hide().next('.image-error').show();
        });
        
        // Add loading state to images
        $('.product-image, .variant-image, .gallery-image').on('load', function() {
            $(this).removeClass('image-loading');
        });
        
        // Add loading state when image starts loading
        $('.product-image, .variant-image, .gallery-image').each(function() {
            if (!this.complete) {
                $(this).addClass('image-loading');
            }
        });
    }
    
    // File Upload Functionality
    $(document).on('click', '.upload-files-btn', function() {
        currentProductId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        $('#uploadProductName').text(productName);
        $('#fileUploadModal').modal('show');
    });
    
    $(document).on('click', '.view-files-btn', function() {
        currentProductId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        $('#galleryProductName').text(productName);
        loadProductFiles(currentProductId);
        $('#fileGalleryModal').modal('show');
    });
    
    // Upload files
    $('#uploadFilesBtn').on('click', function() {
        const files = $('#files')[0].files;
        if (files.length === 0) {
            showUploadStatus('Please select files to upload.', 'danger');
            return;
        }
        
        if (files.length > 10) {
            showUploadStatus('You can upload maximum 10 files at once.', 'danger');
            return;
        }
        
        uploadFiles(files, currentProductId);
    });
    
    // Upload files function
    function uploadFiles(files, productId) {
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }
        
        $('#uploadProgress').show();
        $('#uploadFilesBtn').prop('disabled', true);
        
        $.ajax({
            url: `/inventory/${productId}/upload-files`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        const percentComplete = evt.loaded / evt.total * 100;
                        $('#uploadProgress .progress-bar').css('width', percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                showUploadStatus('Files uploaded successfully!', 'success');
                $('#files').val('');
                setTimeout(() => {
                    $('#fileUploadModal').modal('hide');
                    inventoryTable.ajax.reload(); // Refresh table to show new files
                }, 1500);
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                let errorMessage = 'Upload failed. ';
                if (errors.files) {
                    errorMessage += errors.files.join(', ');
                } else {
                    errorMessage += 'Please try again.';
                }
                showUploadStatus(errorMessage, 'danger');
            },
            complete: function() {
                $('#uploadProgress').hide();
                $('#uploadFilesBtn').prop('disabled', false);
            }
        });
    }
    
    // Load product files
    function loadProductFiles(productId) {
        $.ajax({
            url: `/inventory/${productId}/files`,
            type: 'GET',
            success: function(response) {
                const files = response.files;
                const gallery = $('#fileGallery');
                const noFilesMessage = $('#noFilesMessage');
                
                gallery.empty();
                
                if (files.length === 0) {
                    noFilesMessage.show();
                    gallery.hide();
                } else {
                    noFilesMessage.hide();
                    gallery.show();
                    
                    files.forEach(function(file) {
                        const fileCard = `
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="${file.url}" class="card-img-top gallery-image" alt="${file.file_name}" width="100%" height="200">
                                    <div class="card-body p-2">
                                        <h6 class="card-title small text-truncate" title="${file.file_name}">${file.file_name}</h6>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-primary set-main-btn" data-file-id="${file.id}" title="Set as Main Image">
                                                <i class="fas fa-star"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-file-btn" data-file-id="${file.id}" title="Delete File">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        gallery.append(fileCard);
                    });
                }
            },
            error: function() {
                showUploadStatus('Failed to load files.', 'danger');
            }
        });
    }
    
    // Show upload status
    function showUploadStatus(message, type) {
        const status = $('#uploadStatus');
        status.removeClass('alert-success alert-danger alert-warning alert-info')
              .addClass('alert-' + type)
              .text(message)
              .show();
    }
    
    // Export functionality
    $('#exportBtn').on('click', function() {
        // Get current filter data
        let filters = {
            status: $('#statusTabs .nav-link.active').attr('id').replace('-tab', ''),
            search: $('#searchInput').val(),
            product_filter: $('#productFilter').val(),
            total_filter: $('#totalFilter').val()
        };
        
        // Create export URL with current filters
        let exportUrl = "{{ route('inventory.export') }}?" + $.param(filters);
        
        // Show loading state
        let $btn = $(this);
        let originalText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Exporting...').prop('disabled', true);
        
        // Create temporary link and trigger download
        let link = document.createElement('a');
        link.href = exportUrl;
        link.download = 'inventory_export_' + new Date().toISOString().split('T')[0] + '.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Reset button state
        setTimeout(() => {
            $btn.html(originalText).prop('disabled', false);
        }, 2000);
    });
    
    // Import functionality
    $('#importBtn').on('click', function() {
        // Create file input for import
        let fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = '.csv,.xlsx,.xls';
        fileInput.style.display = 'none';
        
        fileInput.onchange = function(e) {
            let file = e.target.files[0];
            if (file) {
                importInventoryData(file);
            }
        };
        
        document.body.appendChild(fileInput);
        fileInput.click();
        document.body.removeChild(fileInput);
    });
    
    // Import inventory data function
    function importInventoryData(file) {
        let formData = new FormData();
        formData.append('file', file);
        
        // Show loading state
        let $btn = $('#importBtn');
        let originalText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Importing...').prop('disabled', true);
        
        $.ajax({
            url: "{{ route('inventory.import') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert('Import completed successfully! ' + response.message);
                    inventoryTable.ajax.reload(); // Refresh table
                } else {
                    alert('Import failed: ' + response.message);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Import failed. Please check your file format.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
            },
            complete: function() {
                $btn.html(originalText).prop('disabled', false);
            }
        });
    }
    
    // Reset modals when closed
    $('#fileUploadModal').on('hidden.bs.modal', function() {
        $('#files').val('');
        $('#uploadStatus').hide();
        $('#uploadProgress').hide();
    });
    
    $('#fileGalleryModal').on('hidden.bs.modal', function() {
        $('#fileGallery').empty();
        $('#noFilesMessage').hide();
    });
});
</script>
@endsection
