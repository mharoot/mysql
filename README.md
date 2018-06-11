# mysql

### Query Indexing Optimization Techniques
```
--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`parent`);
COMMIT;

--
-- Indexes for table `collectionspkonly`
--
ALTER TABLE `collectionspkonly`
  ADD PRIMARY KEY (`id`);
COMMIT;

--
-- Indexes for table `collectionsparentkeyonly`
--
ALTER TABLE `collectionsparentkeyonly`
  ADD KEY `parent` (`parent`);
COMMIT;
```

### Each one of these techniques has a pro and a con:
- collections
    - pros
        - very fast at gets
    - cons
        - very slow in inserts and updates
- collectionsparentkey
    - pros
        - good balance
    - cons
        - moderate at everything, possible bottle knecks
- collectionspkonly
    - pros
        - moderate at gets
    - cons
        - moderate at inserts and updates


### Query Tuning and Optimization
- If you had written the following code:
```
if ($hasNotBeenVisited && $bound) {
            $this->query("SELECT * FROM $this->_tablename WHERE id = $id"); 
            $item = $this->_stmt->fetch(PDO::FETCH_OBJ);
```
- You may have not noticed how beneficial the `collectionsparentkey` **table** really is.

- If you know your looking for only one row, a LIMIT 1 can save so much time by avoiding a full table scan for our `collectionsparentkey` **table** 
    - pros 
        - very fast at gets
        - excellent balance
        - moderate at inserts and updates 
    - cons
        - Can eventually have its limitations in some scenarios where and pk is needed.

```

if ($hasNotBeenVisited && $bound) {
            $this->query("SELECT * FROM $this->_tablename WHERE id = $id LIMIT 1"); 
            $item = $this->_stmt->fetch(PDO::FETCH_OBJ);
```