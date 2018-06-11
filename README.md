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
        - moderate fast gets
    - cons
        - moderate inserts and updates

