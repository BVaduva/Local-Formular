# Table of Contents

- [Prerequisites](#prerequisites)
- [HTML formula for sending user data to server DB](#html-formula-for-sending-user-data-to-server-db)
- [SQL Query](#sql-query)
- [Database](#database)
- [Docker Containers](#docker-containers)
- [optional - save DB locally](#optional---save-db-locally)

## Prerequisites

- Install WSL2
- Install Ubuntu
- Install Docker Desktop
- _optional_ for DDEV: 
```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072;
iex ((New-Object System.Net.WebClient).DownloadString('https://raw.githubusercontent.com/ddev/ddev/master/scripts/install_ddev_wsl2_docker_desktop.ps1'))
```

## HTML formula for sending user data to server DB

- Simple Login-Form

```html
    <main>
        <div class="container">
            <h1>Login Form</h1>
            <div class="row" id="formFields">
                <form method="POST" action="insert.php">
                    <div class="form-group">
                        <label for="userField">Username</label>
                        <input type="text" name="user" class="form-control" id="userField" aria-describedby="emailHelp"
                            placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                        <label for="emailField">Email address</label>
                        <input type="email" name="email" class="form-control" id="emailField"
                            aria-describedby="emailHelp" placeholder="Enter email" required>
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with
                            anyone!</small>
                    </div>
                    <div class="form-group">
                        <label for="passwordField">Password</label>
                        <input type="password" name="password" class="form-control" id="passwordField"
                            placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
                </form>
            </div>
        </div>
    </main>
```

![Pasted image 20240716103342](https://github.com/user-attachments/assets/15101368-5f92-4047-8fcb-9b63a0441ba9)


---

## SQL Query

```sql
<?php
// Received data from formular
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['user'];
    $email = $_POST['email'];
    $user_password = $_POST['password'];

    // MySQL database credentials
    $servername = "db"; // Use the service name defined in docker-compose
    $username = "root";
    $password = "secret";
    $dbname = "db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $user_password);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
```

---

## Database

- DB management with VS Code extension
- - Get "MySQL" extension
	- create a connection to the Database

>[!Note]
>(Maybe) temporary management of DB

![Pasted image 20240716103555](https://github.com/user-attachments/assets/c498398b-5b65-4fe7-bb3f-3d8c2d4b444a)


Port has to be the one Mapped to (ports: - "3100:3306" in `docker-compose.yml`)
Password has to be the one in `docker-compose.yml` under `MYSQL_ROOT_PASSWORD:`

---
## Docker Containers

- Containers for local formula and db server handling

![Pasted image 20240716103751](https://github.com/user-attachments/assets/93137193-bbe5-4c0d-a404-72e0c5f5f26d)

---

## optional - save DB locally

- docker-compose.yml section to store locally:
```yaml
 db:
#... other settings
    volumes:
	  - db_data:/var/lib/mysql # Mirror local folder and docker to make db_data persistent.
volumes:
  db_data: # Volume to store data
```

---

