# Decision #1: Workflow DSL Specification

---
<a id="top" />

### [Back to Directory](./0-adr-directory.md)

<div style="color:green">

# Proposed

</div>


## Decision

Adopt the Workflow DSL Specification as defined in the [Workflow DSL Specification](./0001-workflow-dsl-specification.pdf).

## Keywords
<ul>
    <li>Workflow Orchestration</li>
    <li>Workflow DSL</li>
    <li>Temporal IO</li>
</ul>

## Argument
The Workflow DSL is a new standard that gives us a right balance between flexibility and robustness without locking
us into using a particular third-party specification.

## Considerations
We must standardize our Workflow DSL specification in order to continue working on the Workflow Orchestration 
microservice.

## Impact

| Positive                                                                                                                                                                          | Negative                                                                                                                                                                                                                                                            |
|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Having this mapping allows us to move forward with expanding functionality of the Workflow Orchestration Microservice to be able to parse and handle flexible DSL specifications. | We will need to support and extend the Workflow DSL, if new requirements arise that we had not foreseen.  This can be potentially difficult and time consuming to implement. |

## Relates To

## Stakeholder Requirements
Stakeholders in this case are internal developers and QA testers.

## Artifacts

## Discussions

## Glossary

[top](#top) 
