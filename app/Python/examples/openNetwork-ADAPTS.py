import frenetic
from frenetic.syntax import *
from settings import Session, engine, Base
from models import Policies
# root_switch port 3> server1
# root_switch port 2> server2
# root_switch port 1> server3

# slave switch Port 3> attacker1
# Slave switch Port 6> attacker2
# Slave switch Port 5> attacker3
# Slave switch Port 4> qvm
# slave switch Port 7 > user1
# Slave switch Port 2> user2


class MyApp(frenetic.App):
    def __init__(self):
        frenetic.App.__init__(self)
        self.topo = {}

    def connected(self):
        root_switch = 240109237270094
        pol = Filter(SwitchEq(root_switch) & IP4DstEq("10.10.2.2")) >> SetPort(3)
        pol = pol | Filter(SwitchEq(root_switch) & IP4DstEq("10.10.3.2")) >> SetPort(2)
        pol = pol | Filter(SwitchEq(root_switch) & IP4DstEq("10.10.4.2")) >> SetPort(1)

        pol = pol | Filter(SwitchEq(root_switch) & IP4DstEq("10.10.2.1")) >> SetPort(3)
        pol = pol | Filter(SwitchEq(root_switch) & IP4DstEq("10.10.3.1")) >> SetPort(2)
        pol = pol | Filter(SwitchEq(root_switch) & IP4DstEq("10.10.4.1")) >> SetPort(1)

        slave_switch_1 = 47138562302797
        pol = pol | Filter(SwitchEq(slave_switch_1) & IP4DstEq("10.10.7.2")) >> SetPort(2)
        pol = pol | Filter(SwitchEq(slave_switch_1) & IP4DstEq("10.10.7.1")) >> SetPort(2)

        pol = pol | Filter(SwitchEq(slave_switch_1) & IP4DstEq("10.10.8.2")) >> SetPort(3)
        pol = pol | Filter(SwitchEq(slave_switch_1) & IP4DstEq("10.10.8.1")) >> SetPort(3)

        pol = pol | Filter(SwitchEq(slave_switch_1) & IP4DstEq("10.10.5.2")) >> SetPort(4)
        pol = pol | Filter(SwitchEq(slave_switch_1) & IP4DstEq("10.10.5.1")) >> SetPort(4)

        pol = pol | Filter(SwitchEq(slave_switch_1) & IP4DstEq("10.10.10.2")) >> SetPort(5)
        pol = pol | Filter(SwitchEq(slave_switch_1) & IP4DstEq("10.10.10.1")) >> SetPort(5)

        pol = pol | Filter(SwitchEq(slave_switch_1) & IP4DstEq("10.10.9.2")) >> SetPort(6)
        pol = pol | Filter(SwitchEq(slave_switch_1) & IP4DstEq("10.10.9.1")) >> SetPort(6)

        pol = pol | Filter(SwitchEq(slave_switch_1) & IP4DstEq("10.10.6.2")) >> SetPort(7)
        pol = pol | Filter(SwitchEq(slave_switch_1) & IP4DstEq("10.10.6.1")) >> SetPort(7)

        session = Session()
        policies = session.query(Policy.policy).filter_by(loaded=1)
        custom_policies =''
        for p in Policies:
            custom_policies = custom_policies | p.policy

        print(custom_policies)
        pol = pol | custom_policies



        app.update(pol)
        # The controller may already be connected to several switches on startup.
        # This ensures that we probe them too.

    #        def handle_current_switches(switches):
    #            for switch_id in switches:
    #                self.switch_up(switch_id, switches[switch_id])
    #        self.current_switches(callback=handle_current_switches)

    def policy(self):
        return Union(self.sw_policy(sw) for sw in self.topo.keys())

    def sw_policy(self, sw):
        ports = self.topo[sw]
        p = Union(self.port_policy(in_port, ports) for in_port in ports)
        return Filter(SwitchEq(sw)) >> p

    def port_policy(self, in_port, ports):
        p = SetPort([port for port in ports if port != in_port])
        return Filter(PortEq(in_port)) >> p

    def switch_up(self, switch_id, ports):
        self.topo[switch_id] = ports
        app.update(self.policy())

    def switch_down(self, switch_id):
        del self.topo[switch_id]
        app.update(self.policy())

    def port_up(self, switch_id, port_id):
        self.topo[switch_id].append(port_id)
        app.update(self.policy())

    def port_down(self, switch_id, port_id):
        self.topo[switch_id].remove(port_id)
        app.update(self.policy())


app = MyApp()
app.start_event_loop()
