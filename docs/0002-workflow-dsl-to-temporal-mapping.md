# Decision #1: Serverless Workflow DSL to Temporal Mapping

---
<a id="top" />

### [Back to Directory](./0-adr-directory.md)

<div style="color:green">

# Proposed

</div>


## Decision
Adopt the Workflow DSL to Temporal mapping as defined in the [Workflow DSL Mapping Document](./0002-workflow-dsl-mapping.pdf).

## Keywords
<ul>
    <li>Workflow Orchestration</li>
    <li>Serverless Workflow DSL</li>
    <li>Temporal IO</li>
</ul>

## Argument

Proper mapping of the Workflow DSL to Temporal.io workflows is necessarily in order to implement a DSL interpreter to execute DSL Workflows.

## Considerations
Without this we cannot implement the DSL workflows.

## Impact

| Positive                                                                                                                                                                          | Negative                                                                                                                                                                                                       |
|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Having this mapping allows us to move forward with expanding functionality of the Workflow Orchestration Microservice to be able to parse and handle flexible DSL specifications. | We are locked into the Workflow DSL and Temporal IO spec.  It's potentially difficult and time consuming to implement this as currently such a mapping only exists in the Temporal IO samples-java repository. |

## Relates To

## Stakeholder Requirements
Stakeholders in this case are internal developers and QA testers.

## Artifacts

## Discussions

## Glossary

[top](#top) 
