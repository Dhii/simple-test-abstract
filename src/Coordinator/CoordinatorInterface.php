<?php

namespace Dhii\SimpleTest\Coordinator;

/**
 * Something that can act as a coordinator.
 *
 * A coordinator is something that accepts notifications. It may then do
 * whatever it needs with them.
 *
 * The concept is similar to events, but greatly simplified. Events are
 * triggered by running a method on the coordinator, and the method itself
 * is the even handler. Therefore, the coordinator is the one and only
 * observer of its own events.
 *
 * If a coordinator method is declared, it gets called with 2 parameters:
 *  1. The event subject, which is the main data of the event;
 *  2. The event source, which is what triggered the event.
 * It is NOT REQUIRED to specify both arguements, although highly RECOMMENDED,
 * and this requirement depends on the method declaration, which is a
 * declarative way (no pun intended) to define parameters. However, by
 * convention, the order of parameters MUST always be the above; `null` must
 * be specified if a parameter is not available. Parameters that are optional
 * MUST be null by default.
 *
 * When a coordinator method is not declared, nothing MUST happen, and the event
 * MUST. This can be done by declaring the magic `__call()` method.
 * A "catch-all" method `_any()` SHOULD be called by it, and if declared it
 * MUST accept the name of the called method as the first parameter, followed by
 * the parameters explained above.
 *
 * @since [*next-version*]
 */
interface CoordinatorInterface
{
}
