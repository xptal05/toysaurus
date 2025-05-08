
<h2>Manage Users</h2>

<table>
    <tr><th>Email</th><th>Role</th><th>Actions</th></tr>
</table>


<?php
include_once BASE_PATH.'/controllers/ClientController.php';

$clients = ClientController::getAllClients();
foreach ($clients as $client) {
    echo "Client: " . $client->getFirstName() . " " . $client->getLastName();
    echo " - Profile Photo: " . ($client->getPhoto() ? $client->getPhoto()->getName() : "No Photo");
    echo "<br>";
}

?>

<script type="module">
import { getAuth, getIdToken } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-auth.js";
import { getDatabase, ref, get } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-database.js";

const auth = getAuth();
const db = getDatabase();

get(ref(db, "users")).then(snapshot => {
    if (snapshot.exists()) {
        snapshot.forEach(childSnapshot => {
            const user = childSnapshot.val();
            document.querySelector("table").innerHTML += `
                <tr>
                    <td>${user.email}</td>
                    <td>${user.role || "User"}</td>
                    <td><button onclick="makeAdmin('${user.uid}')">Make Admin</button></td>
                </tr>
            `;
        });
    }
});
</script>


