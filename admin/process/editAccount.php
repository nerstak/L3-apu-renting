<?php
/**
 * Display form to edit account
 */

require_once(RES_SQL);

if (!isset($_GET['id'])) {
    header("Location: accounts.php");
}

// Display values
$dataUser = dataSelectionUser($dbConnection, $_GET['id']);

echo '<div id="editAccount" class="boxStyle"><form class="classicForm" autocomplete="off" action="accounts.php?content=edit&id='.$_GET['id'].'" method="POST">
                <h2>Update information</h2>';
echo '<input type="hidden" name="idEdit" value="' . $_GET['id'] . '">';
echo '<div>
                    <label>Email </label><br><input type="email" name="mailEdit" value="' . $dataUser['email'] . '" required>
                </div>';
echo '<div>
                <label>Fornames </label><br><input type="text" name="fornamesEdit" value="' . $dataUser['firstName'] . '" required>
            </div>';
echo '<div>
                <label>Last names </label><br><input type="text" name="lastnamesEdit" value="' . $dataUser['lastNames'] . '" required>
            </div>';
echo '<div>
                    <label>Address:</label><br><textarea name="addressEdit" value="default">' . $dataUser['address'] . '</textarea>
                </div>';
$req = $dataUser['role'] == 'admin' ? "selected" : " ";

echo '<div>
    <label>Role</label><br>
    <select name="roleEdit">
  <option value="user">User</option>
  <option value="admin" ' . $req . '>Admin</option>
</select>
</div>';
echo '<div>
                    <label>Password </label><br>
                    <input type="password" name="passwordEdit1" oninput="checkEdit()">
                    <div class="tooltip" style="display:none" id="errorMessageEditP1">
                        <i class="material-icons md-24">error_outline</i>
                        <span class="tooltipText"></span>
                    </div>
                </div>
                <div>
                    <label>Confirm password </label><br>
                    <input type="password" name="passwordEdit2" oninput="checkEdit()">
                    <div class="tooltip" style="display:none" id="errorMessageEditP2">
                        <i class="material-icons md-24">error_outline</i>
                        <span class="tooltipText"></span>
                    </div>
                </div>
                <p style="display:none" id="errorMessageEdit"></p>';

$select = $dataUser['visible'] == true ? "selected" : "";
echo '<div><label>Visible: </label><br><select name="visibleEdit"><option value="0">False</option><option value="1" ' . $select . '>True</option></select></div>';


echo'<button id="editButton" type="submit" name="editForm" class="btn-success">Edit account</button>
            </form>
        </div>';

