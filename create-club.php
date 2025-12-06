<?php 
require_once 'auth.php';
require('connect-db.php');
require('clubs-db.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['club_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if (empty($name)) {
        $error = 'Club name is required.';
    } elseif (empty($description)) {
        $error = 'Description is required.';
    } else {
        $result = createClub($name, $description, $_SESSION['user_id']);
        
        if ($result['success']) {
            header('Location: club-details.php?clubName=' . urlencode($result['club_name']));
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Book Club - TopShelf</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            font-family: inherit;
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .error-message {
            background-color: #fee;
            color: #c33;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .submit-btn {
            background-color: #4a90e2;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
        }
        
        .submit-btn:hover {
            background-color: #357abd;
        }
        
        .cancel-btn {
            display: inline-block;
            text-align: center;
            background-color: #6c757d;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 1rem;
            width: 100%;
        }
        
        .cancel-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <?php include('header.php'); ?>

    <section class="hero">
        <h1>Create a Book Club</h1>
    </section>

    <div class="container">
        <div class="form-container">
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="create-club.php">
                <div class="form-group">
                    <label for="club_name">Club Name *</label>
                    <input 
                        type="text" 
                        id="club_name" 
                        name="club_name" 
                        required
                        maxlength="100"
                        value="<?php echo htmlspecialchars($_POST['club_name'] ?? ''); ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        required
                        maxlength="500"
                    ><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Create Club</button>
                <a href="book-clubs.php" class="cancel-btn">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>