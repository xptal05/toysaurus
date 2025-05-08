<form id="mediaUploadForm" method="POST" enctype="multipart/form-data">
  <label for="entityType">Entity Type:</label>
  <select name="entityType" id="entityType" required>
    <option value="">-- Select --</option>
    <option value="client">Client</option>
    <option value="document">Document</option>
    <option value="toy">Toy</option>
  </select>

  <label for="entityId">Entity ID:</label>
  <input type="number" name="entityId" id="entityId" required />

  <label for="role">Role:</label>
  <select name="role" id="role" required>
    <option value="main_photo">Main</option>
    <option value="gallery_photo">Gallery</option>
    <option value="document">Document</option>
    <option value="profile">Profile</option>
    <option value="other">Other</option>
  </select>

  <div id="dropZone" class="drop-zone">
    <p>Drag & drop files here or click to upload</p>
    <input type="file" name="media[]" id="media" multiple style="display: none;" />
  </div>

  <ul id="fileList"></ul>

  <button type="submit">Upload</button>
</form>

<style>
  .drop-zone {
    border: 2px dashed #999;
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    margin-top: 10px;
  }

  .drop-zone.dragover {
    background-color: #f0f0f0;
  }
</style>


<script>



</script>