const express = require('express');
const fs = require('fs');
const path = require('path');
const app = express();
const PORT = 3022;

// Set up middleware
app.use(express.json());
app.use(express.static(__dirname)); // Serve static files (index.html, CSS, etc.)

const TEMPLATES_FILE = path.join(__dirname, 'templates.json');
const SUBMISSIONS_FILE = path.join(__dirname, 'submissions.json');

// Ensure the JSON files exist with default data
if (!fs.existsSync(TEMPLATES_FILE)) {
    fs.writeFileSync(TEMPLATES_FILE, JSON.stringify({
        "ResumeModify-v2": "Task: Modify my resume for the following job description:\n\n<insert_biz_info>",
        "Customize Resume, and Get 2 Cover Letters - v1.0": "Task: Create a resume and two cover letters aligning with the job description:\n\n<insert_biz_info>",
        "Basic Resume Optimizer Prompt - and Cover Letter Gen": "Optimize my resume and generate a tailored cover letter for the following:\n\n<insert_biz_info>"
    }, null, 2));
}

if (!fs.existsSync(SUBMISSIONS_FILE)) {
    fs.writeFileSync(SUBMISSIONS_FILE, JSON.stringify([]));
}

// Endpoint to get templates
app.get('/templates.json', (req, res) => {
    fs.readFile(TEMPLATES_FILE, (err, data) => {
        if (err) {
            res.status(500).json({ error: 'Error reading templates file' });
        } else {
            res.json(JSON.parse(data));
        }
    });
});

// Endpoint to save submissions
app.post('/save_submission', (req, res) => {
    fs.readFile(SUBMISSIONS_FILE, (err, data) => {
        if (err) return res.status(500).json({ error: 'Error reading submissions file' });

        let submissions = JSON.parse(data);
        submissions.push(req.body);

        fs.writeFile(SUBMISSIONS_FILE, JSON.stringify(submissions, null, 2), (err) => {
            if (err) return res.status(500).json({ error: 'Error saving submission' });
            res.json({ message: 'Submission saved successfully!' });
        });
    });
});

app.listen(PORT, () => {
    console.log(`Server running at http://localhost:${PORT}`);
});
