# Voting System Function Documentation

This document provides in-depth documentation for all major functions involved in the election results flow, including their structure, logic, and syntax. It covers:
- control/resultsControl.php
- model/createOperations.php
- model/readOperations.php

---

## control/resultsControl.php

### getUserInfoController()
**Purpose:**
- Checks if a user is logged in and retrieves their username and role.

**Logic:**
- Checks `$_SESSION['username']` and `$_SESSION['role']`.
- Determines if the user is an admin (`role === "1000"`).
- Returns a structured array with user info and admin status.

**Syntax:**
```php
function getUserInfoController() { ... }
```

---

### getElectionController()
**Purpose:**
- Fetches the current election from the database.

**Logic:**
- Calls `getCurrentElection()` (from readOperations.php).
- Returns election data or an error if none exists.

**Syntax:**
```php
function getElectionController() { ... }
```

---

### checkElectionStatusController($election, $is_admin)
**Purpose:**
- Determines if the election is ongoing or ended, and if results should be restricted.

**Logic:**
- Uses `isElectionOngoing($end_date)` (from createOperations.php).
- If ongoing and not admin, restricts results.
- Returns election status and restriction info.

**Syntax:**
```php
function checkElectionStatusController($election, $is_admin) { ... }
```

---

### fetchResultsController($election_id)
**Purpose:**
- Fetches and formats election results for a given election ID.

**Logic:**
- Calls `getElectionResults($election_id)` (from createOperations.php).
- Returns results data or error.

**Syntax:**
```php
function fetchResultsController($election_id) { ... }
```

---

### handleGetResultsController()
**Purpose:**
- Main handler for GET requests. Orchestrates user, election, and results logic.

**Logic:**
- Gets user info, election info, checks status, fetches results if allowed.
- Returns a structured response for the frontend.

**Syntax:**
```php
function handleGetResultsController() { ... }
```

---

## model/createOperations.php

### isElectionOngoing($end_date)
**Purpose:**
- Checks if the current date is before the election end date.

**Logic:**
- Compares current time with `$end_date` using PHP's DateTime.
- Returns boolean.

**Syntax:**
```php
function isElectionOngoing($end_date) { ... }
```

---

### calculateVotePercentage(&$candidates)
**Purpose:**
- Calculates the percentage of votes for each candidate.

**Logic:**
- Sums total votes.
- For each candidate, computes `(vote_count / total_votes) * 100`.
- Adds a `percentage` field to each candidate.

**Syntax:**
```php
function calculateVotePercentage(&$candidates) { ... }
```

---

### getElectionResults($election_id)
**Purpose:**
- Aggregates all results for an election, including positions and candidates.

**Logic:**
- Gets all positions for the election (`getPositions`).
- For each position, gets candidates (`getCandidates`) and calculates percentages.
- Returns a structured array with all results.

**Syntax:**
```php
function getElectionResults($election_id) { ... }
```

---

## model/readOperations.php

### getCurrentElection()
**Purpose:**
- Fetches the most recent election from the database.

**Logic:**
- SQL: `SELECT ... FROM Elections ORDER BY election_id DESC LIMIT 1`.
- Returns election data or null.

**Syntax:**
```php
function getCurrentElection() { ... }
```

---

### getPositions($election_id)
**Purpose:**
- Fetches all positions for a given election.

**Logic:**
- SQL: `SELECT position_id, position_name FROM Positions WHERE election_id = $eid ...`.
- Returns an array of positions or null.

**Syntax:**
```php
function getPositions($election_id) { ... }
```

---

### getCandidates($position_id, $election_id)
**Purpose:**
- Fetches all candidates for a position in an election, including their vote counts.

**Logic:**
- SQL joins Candidates, Students, and Votes tables.
- Groups by candidate, counts votes, sorts by vote count.
- Returns an array of candidates with vote counts.

**Syntax:**
```php
function getCandidates($position_id, $election_id) { ... }
```

---

## Data Flow Summary
1. **Frontend** calls resultsControl.php via AJAX.
2. **resultsControl.php** checks session, gets election, checks status, fetches results.
3. **createOperations.php** aggregates and calculates results.
4. **readOperations.php** fetches raw data from the database.
5. **Frontend** receives JSON and renders charts and winner cards.

---

## Notes
- All functions use structured arrays for consistent data exchange.
- Error handling is present at each step to ensure robust responses.
- The system is modular: frontend, controller, logic, and DB operations are separated for maintainability.
