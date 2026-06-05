<?php
// News Portal - Installation Script
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install - News Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
        .install-card { border: none; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .install-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0; padding: 30px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card install-card">
                    <div class="install-header">
                        <i class="fas fa-newspaper fa-3x mb-3"></i>
                        <h3>News Portal Installation</h3>
                    </div>
                    <div class="card-body p-4">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $host = $_POST['host'];
                            $dbname = $_POST['dbname'];
                            $user = $_POST['user'];
                            $pass = $_POST['pass'];

                            try {
                                $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $sql = file_get_contents('database.sql');
                                $pdo->exec($sql);

                                $config = "<?php\n";
                                $config .= "\$host = '$host';\n";
                                $config .= "\$dbname = '$dbname';\n";
                                $config .= "\$username = '$user';\n";
                                $config .= "\$password = '$pass';\n\n";
                                $config .= "try {\n";
                                $config .= "    \$pdo = new PDO(\"mysql:host=\$host;dbname=\$dbname;charset=utf8mb4\", \$username, \$password);\n";
                                $config .= "    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n";
                                $config .= "    \$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);\n";
                                $config .= "} catch(PDOException \$e) {\n";
                                $config .= "    die(\"Connection failed: \" . \$e->getMessage());\n";
                                $config .= "}\n\n";
                                $config .= "function getSettings(\$pdo) {\n";
                                $config .= "    \$stmt = \$pdo->query(\"SELECT * FROM settings\");\n";
                                $config .= "    \$settings = [];\n";
                                $config .= "    while (\$row = \$stmt->fetch()) {\n";
                                $config .= "        \$settings[\$row['key_name']] = \$row;\n";
                                $config .= "    }\n";
                                $config .= "    return \$settings;\n";
                                $config .= "}\n";
                                $config .= "?>";

                                file_put_contents('config/database.php', $config);

                                echo '<div class="alert alert-success">
                                    <h5><i class="fas fa-check-circle"></i> Installation Successful!</h5>
                                    <p>Database created and configured successfully.</p>
                                    <hr>
                                    <p><strong>Admin Login:</strong></p>
                                    <p>Username: <code>admin</code></p>
                                    <p>Password: <code>password</code></p>
                                    <a href="admin/index.php" class="btn btn-primary">Go to Admin Panel</a>
                                    <a href="index.php" class="btn btn-secondary">View Website</a>
                                    <br><br>
                                    <div class="alert alert-warning mb-0">
                                        <small><i class="fas fa-exclamation-triangle"></i> Please delete <strong>install.php</strong> and <strong>database.sql</strong> after installation for security.</small>
                                    </div>
                                </div>';
                            } catch (Exception $e) {
                                echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                            }
                        } else {
                        ?>
                        <p class="text-muted mb-4">Enter your MySQL database details to install the news portal.</p>
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Database Host</label>
                                    <input type="text" name="host" class="form-control" value="localhost" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Database Name</label>
                                    <input type="text" name="dbname" class="form-control" value="news_db" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="user" class="form-control" value="root" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="pass" class="form-control" placeholder="Leave empty if no password">
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> This will create the database and all tables automatically.
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">Install Now</button>
                        </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
