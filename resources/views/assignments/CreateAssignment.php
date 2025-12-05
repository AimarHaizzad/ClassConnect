<div class="card p-4">
    <h3>Create New Assignment</h3>
    <form action="/assignments/store" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"> 

        <div class="form-group mb-3">
            <label for="title">Title & Class*</label>
            <input type="text" id="title" name="title" class="form-control" required placeholder="e.g., Exponential number CLS1001 (Mathematics)">
        </div>

        <div class="form-group mb-3">
            <label for="description">Assignment Description*</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="attachment">Upload Attachment (Optional)</label>
            <input type="file" id="attachment" name="attachment" class="form-control-file">
            </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label for="due_date">Due Date*</label>
                <input type="date" id="due_date" name="due_date" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="due_time">Time*</label>
                <input type="time" id="due_time" name="due_time" class="form-control" required>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </form>
</div>