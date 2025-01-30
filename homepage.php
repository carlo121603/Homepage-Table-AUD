<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minimal Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="homepage_style.css">
</head>
<body class="bg-light">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top px-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button class="btn btn-success me-2 btn-sm mt-1" onclick="openAddClientForm()">Add Client</button>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-danger btn-sm mt-1" onclick="logout()">Logout</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Add Client Form -->
    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-12">
                <div id="addClientForm" class="container mt-5 pt-5" style="display:none;">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Add New Client</h5>
                        </div>
                        <div class="card-body">
                            <form action="Add_Clients.php" method="POST" onsubmit="return validateForm()" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="add">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="clientName" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="clientName" name="name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="clientEmail" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="clientEmail" name="email">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="clientNumber" class="form-label">Number</label>
                                            <input type="tel" class="form-control" id="clientNumber" name="number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="clientImage" class="form-label">Profile Image</label>
                                        <input type="file" class="form-control" id="clientImage" name="image" accept="image/*">
                                    </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                                <button type="button" class="btn btn-secondary" onclick="closeAddClientForm()">Close</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Data Table -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">Client Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Number</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once 'db_connection.php';
                                    $query = "SELECT * FROM users_info ORDER BY id DESC";
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                            <td>
                                                <img src='" . ($row['image_path'] ? $row['image_path'] : 'path/to/default-image.png') . "' 
                                                    alt='' 
                                                    class='rounded-circle'
                                                    style='max-width: 40px; max-height: 40px; width: 100%; height: auto; object-fit: cover;'>
                                            </td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['number']}</td>
                                            <td>
                                                <a href='Add_Clients.php?id={$row['id']}' class='btn btn-primary btn-sm'>Edit</a>
                                                <form action='Add_Clients.php' method='POST' style='display:inline;' onsubmit='confirmDelete(event)'>
                                                    <input type='hidden' name='action' value='delete'>
                                                    <input type='hidden' name='id' value='{$row['id']}'>
                                                    <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                                                </form>
                                            </td>
                                        </tr>";
                                    }
                                    mysqli_close($conn);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openAddClientForm() {
            document.getElementById('addClientForm').style.display = 'block';
        }

        function closeAddClientForm() {
            document.getElementById('addClientForm').style.display = 'none';
        }

        function confirmDelete(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
        }

        function validateForm() {
            const name = document.getElementById('clientName').value;
            const email = document.getElementById('clientEmail').value;
            const number = document.getElementById('clientNumber').value;
            const image = document.getElementById('clientImage').files[0];

            if (!name || !email || !number || !image) {
                let errorMessage = 'Please fill in all fields!';
                if (!image) {
                    errorMessage = 'Please select a profile image!';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: errorMessage
                });
                return false;
            }

            // Validate image size and type
            if (image) {
                const fileSize = image.size / (1024 * 1024); // Convert to MB
                const fileType = image.type.split('/')[1].toLowerCase();
                const allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

                if (fileSize > 5) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Image size should not exceed 5MB'
                    });
                    return false;
                }

                if (!allowedTypes.includes(fileType)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Please upload a valid image file (JPG, JPEG, PNG, or GIF)'
                    });
                    return false;
                }
            }

            return true;
        }

        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const message = urlParams.get('message');

        if (status && message) {
            Swal.fire({
                icon: status,
                title: status.charAt(0).toUpperCase() + status.slice(1),
                text: message
            }).then(() => {
                const newUrl = window.location.href.split('?')[0];
                history.replaceState(null, '', newUrl);
            });
        }


        function logout() {
        // Show SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Logout',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the specified URL if confirmed
                window.location.href = "http://localhost/Task1-Neuralcore/index.php";
            } else {
                // Optionally, you can add any action when the user clicks "Cancel"
                console.log("Logout canceled");
            }
        });
    }
    </script>
</body>
</html>
