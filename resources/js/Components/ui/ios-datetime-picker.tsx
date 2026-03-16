import * as React from "react";
import { format } from "date-fns";
import { id } from "date-fns/locale";
import { Calendar } from "@/Components/ui/calendar";
import { TimePicker } from "@/Components/ui/time-picker";
import { Popover, PopoverContent, PopoverTrigger } from "@/Components/ui/popover";
import { Button } from "@/Components/ui/button";
import { CalendarIcon, Clock } from "lucide-react";
import { cn } from "@/lib/utils";

interface IosDateTimePickerProps {
    name: string;
    initialValue?: string;
    label?: string;
}

export function IosDateTimePicker({ name, initialValue, label }: IosDateTimePickerProps) {
    const [date, setDate] = React.useState<Date | undefined>(
        initialValue ? new Date(initialValue) : new Date()
    );
    const [isOpen, setIsOpen] = React.useState(false);

    const formattedValue = date ? format(date, "yyyy-MM-dd HH:mm") : "";
    const displayValue = date ? format(date, "dd/MM/yyyy, HH.mm") : "Pilih waktu...";

    // Handle time change from our new TimePicker
    const handleTimeChange = (newDate: Date) => {
        setDate(newDate);
    };

    return (
        <div className="ios-datetime-picker-wrapper">
            <input type="hidden" name={name} value={formattedValue} />
            
            <Popover open={isOpen} onOpenChange={setIsOpen}>
                <PopoverTrigger asChild>
                    <Button
                        variant={"outline"}
                        className={cn(
                            "w-full justify-start text-left font-normal h-10 border-[#d1d5db] rounded-lg bg-white hover:border-primary/50 transition-all",
                            !date && "text-muted-foreground"
                        )}
                    >
                        <span className="flex-1 font-semibold">{displayValue}</span>
                        <CalendarIcon className="ml-2 h-4 w-4 opacity-50" />
                    </Button>
                </PopoverTrigger>
                <PopoverContent className="w-auto p-0 border-none shadow-2xl rounded-2xl overflow-hidden bg-white" align="start">
                    <div className="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-gray-100">
                        {/* Calendar Part */}
                        <div className="p-4">
                            <div className="flex flex-col mb-3 px-2 text-primary">
                                <span className="text-[10px] uppercase tracking-wider font-bold opacity-70">Tanggal Terpilih</span>
                                <span className="text-sm font-bold">
                                    {date ? format(date, "EEEE, dd MMMM yyyy", { locale: id }) : "Pilih Tanggal"}
                                </span>
                            </div>
                            <Calendar
                                mode="single"
                                selected={date}
                                onSelect={(d) => d && setDate(d)}
                                locale={id}
                                initialFocus
                                className="rounded-md border-none"
                            />
                        </div>

                        {/* Clock Part */}
                        <div className="p-4 bg-gray-50/30 flex flex-col items-center min-w-[160px]">
                            <div className="flex items-center gap-2 mb-3 text-primary font-bold self-start px-2">
                                <Clock size={16} />
                                <span className="text-xs uppercase tracking-wider">Pilih Waktu</span>
                            </div>
                            
                            <TimePicker 
                                value={date} 
                                onChange={handleTimeChange} 
                            />
                            
                            <div className="mt-8 w-full px-2">
                                <Button 
                                    className="w-full bg-primary hover:bg-primary/90 text-white rounded-xl h-10 shadow-lg shadow-primary/20 font-bold"
                                    onClick={() => setIsOpen(false)}
                                >
                                    Selesai
                                </Button>
                            </div>
                        </div>
                    </div>
                </PopoverContent>
            </Popover>
        </div>
    );
}
