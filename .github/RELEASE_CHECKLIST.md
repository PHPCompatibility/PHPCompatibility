Release checklist
-----------------

Use the below text for a release PR to merge `develop` into `master` and adjust the placeholders between [square brackets] to the correct information for that release:

---

:warning: **DO NOT MERGE (YET)** :warning:

PR for tracking changes for the [x.x.x] release. Target release date: **[Weekday Month Day Year]**.

## Release checklist

### Release preparation
- [ ] Verify, and if necessary, update the allowed version ranges for various dependencies in the `composer.json` - PR #[#]
- [ ] [Major releases only] Update the aliases in `extra -> branch-alias` in the `composer.json` file
- [ ] [Major releases only] Update the `COMPOSER_ROOT_VERSION` in the `.travis.yml` file
- [ ] Add changelog for the release - PR #[#]
    :pencil2: _Remember to create a link to the milestone and to the diff at the bottom of the file._
    
### Release
- [ ] Merge this PR
- [ ] Add release tag against `master` (careful, GH defaults to `develop`!) & copy & paste the changelog to it
    :pencil2: _Make sure to copy the relevant links from the bottom of the changelog as well._
- [ ] Close the milestone for the release
- [ ] Open a new milestone for the next release
- [ ] If any open PRs which were milestoned for this release do not make it into the release, update their milestone.
- [ ] Tweet about the release.
- [ ] Post about it in Slack. (WordPress #tide channel and any others people deem relevant)
- [ ] Fast-forward `develop` to be equal to `master`

---